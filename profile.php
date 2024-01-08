<?php
    include 'config.php';

    session_start();

    if (!isset($_SESSION['id_utente'])) {
        header("Location: login.php");
        exit();
    }

    $resultMessage = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update user data if form is submitted
        if (isset($_POST['update_user'])) {
            try {
                $newNome = $_POST['new_nome'];
                $newCognome = $_POST['new_cognome'];
                $newUsername = $_POST['new_username'];
                $newEmail = $_POST['new_email'];

                // Update user data in the database
                $updateUserDataSql = "UPDATE Utenti SET nome = ?, cognome = ?, username = ?, mail = ? WHERE id_utente = ?";
                $updateUserDataStmt = $conn->prepare($updateUserDataSql);
                $updateUserDataStmt->bind_param("ssssi", $newNome, $newCognome, $newUsername, $newEmail, $_SESSION['id_utente']);
                $updateUserDataStmt->execute();
                $updateUserDataStmt->close();
                $resultMessage = "Dati aggiornati con successo!";
            } catch (Exception $e) {
                $resultMessage = "Errore durante l'aggiornamento dei dati";
            }
        }

        // Add new contact if form is submitted
        if (isset($_POST['add_contact'])) {
            try {
                $newContactUsername = $_POST['new_contact_username'];

                // Check if the contact's username exists in the Utenti table
                $checkContactSql = "SELECT id_utente FROM Utenti WHERE username = ?";
                $checkContactStmt = $conn->prepare($checkContactSql);
                $checkContactStmt->bind_param("s", $newContactUsername);
                $checkContactStmt->execute();
                $checkContactResult = $checkContactStmt->get_result();

                if ($checkContactResult->num_rows > 0) {
                    // Contact username exists, check if a chat already exists
                    $contactUserId = $checkContactResult->fetch_assoc()['id_utente'];

                    $existingChatSql = "SELECT id_chat FROM Chat WHERE (partecipante1 = ? AND partecipante2 = ?) OR (partecipante1 = ? AND partecipante2 = ?)";
                    $existingChatStmt = $conn->prepare($existingChatSql);
                    $existingChatStmt->bind_param("iiii", $_SESSION['id_utente'], $contactUserId, $contactUserId, $_SESSION['id_utente']);
                    $existingChatStmt->execute();
                    $existingChatResult = $existingChatStmt->get_result();

                    if ($existingChatResult->num_rows > 0) {
                        // Chat already exists
                        $resultMessage = "Una chat con il contatto esiste già!";
                    } else {
                        // Initialize a new chat between the current user and the added contact
                        $initializeChatSql = "INSERT INTO Chat (statoChat, partecipante1, partecipante2) VALUES (?, ?, ?)";
                        $initializeChatStmt = $conn->prepare($initializeChatSql);
                        $chatState = "Active";  // You can set the initial chat state as needed
                        $initializeChatStmt->bind_param("sii", $chatState, $_SESSION['id_utente'], $contactUserId);
                        $initializeChatStmt->execute();
                        $initializeChatStmt->close();

                        $resultMessage = "Chat con il nuovo contatto inizializzata con successo!";
                    }
                } else {
                    $resultMessage = "Errore: L'username del contatto non esiste.";
                }

                $checkContactStmt->close();
                $existingChatStmt->close();
            } catch (Exception $e) {
                $resultMessage = "Errore durante l'inizializzazione della chat con il nuovo contatto.";
            }
        }


        // Remove contact if form is submitted
        if (isset($_POST['remove_contact'])) {
            try {
                $contactUsernameToRemove = $_POST['contact_username_to_remove'];

                // Check if the contact's username exists in the Utenti and Contatti tables
                $checkContactSql = "SELECT c.partecipante1, c.partecipante2, c.id_chat, c.utente_id
                                    FROM Chat c
                                    WHERE (c.partecipante1 = ? AND c.partecipante2 = ?) OR (c.partecipante1 = ? AND c.partecipante2 = ?)";
                $checkContactStmt = $conn->prepare($checkContactSql);
                $checkContactStmt->bind_param("is", $_SESSION['id_utente'], $contactUsernameToRemove, $contactUsernameToRemove, $_SESSION['id_utente']);
                $checkContactStmt->execute();
                $checkContactResult = $checkContactStmt->get_result();

                if ($checkContactResult->num_rows > 0) {
                    // Contact exists, remove it from the Contatti table
                    $contactData = $checkContactResult->fetch_assoc();
                    $contactIdToRemove = $contactData['id_contatto'];

                    $removeContactSql = "DELETE FROM Contatti WHERE id_contatto = ?";
                    $removeContactStmt = $conn->prepare($removeContactSql);
                    $removeContactStmt->bind_param("i", $contactIdToRemove);
                    $removeContactStmt->execute();
                    $removeContactStmt->close();

                    // Optional: You may want to also remove associated chat or messages here
                    // Remove associated chat
                    $removeChatSql = "DELETE FROM Chat WHERE id_chat = ? AND utente_id = ? AND utente_contatto_id = ?";
                    $removeChatStmt = $conn->prepare($removeChatSql);
                    $removeChatStmt->bind_param("iii", $contactData['id_contatto'], $_SESSION['id_utente'], $contactData['utente_id']);
                    $removeChatStmt->execute();
                    $removeChatStmt->close();

                    $resultMessage = "Contatto rimosso con successo!";
                } else {
                    $resultMessage = "Errore: Il contatto non esiste o non ti appartiene.";
                }

                $checkContactStmt->close();
            } catch (Exception $e) {
                $resultMessage = "Errore durante la rimozione del contatto.";
            }
        }
    }

    // Retrieve user data
    $sql = "SELECT * FROM Utenti WHERE id_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['id_utente']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html5>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profilo</title>
    </head>
    <body>
        <!-- view user data -->
        <!-- Editable user data form -->
        <h1>Profilo</h1>
        <p>ID: <?php echo $row["id_utente"] ?></p>
        <form method="post" action="">
            <label for="new_nome">Nome:</label>
            <input type="text" name="new_nome" value="<?php echo $row['nome']; ?>" required><br>

            <label for="new_cognome">Cognome:</label>
            <input type="text" name="new_cognome" value="<?php echo $row['cognome']; ?>" required><br>

            <label for="new_username">Username:</label>
            <input type="text" name="new_username" value="<?php echo $row['username']; ?>" required><br>

            <label for="new_email">Email:</label>
            <input type="email" name="new_email" value="<?php echo $row['mail']; ?>" required><br>

            <input type="submit" name="update_user" value="Salva modifiche">
        </form>

        <p><?php echo $resultMessage; ?></p>

        <h3>Add new contact</h3>
        <form method="post" action="">
            <label for="new_contact_username">Username del contatto:</label>
            <input type="text" name="new_contact_username" required><br>

            <input type="submit" name="add_contact" value="Aggiungi contatto">
        </form>

        <h3>Remove contact</h3>
        <form method="post" action="">
            <label for="contact_username_to_remove">Username del contatto da rimuovere:</label>
            <input type="text" name="contact_username_to_remove" required><br>

            <input type="submit" name="remove_contact" value="Rimuovi contatto">
        </form>

        <a href="home.php">Home</a>
        <br>
        <a href="logout.php">Logout</a>
        <br>
        <a href="delete_account.php">Elimina account</a>
    </body>
</html>
