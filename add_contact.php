<?php
include 'config.php';

session_start();

if (!isset($_SESSION['id_utente'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $newContactUsername = $_POST['new_contact_username'];
        $newContactName = $_POST['new_contact_name'];

        // Check if the contact's username exists in the Utenti table
        $checkContactSql = "SELECT id_utente FROM Utenti WHERE username = ?";
        $checkContactStmt = $conn->prepare($checkContactSql);
        $checkContactStmt->bind_param("s", $newContactUsername);
        $checkContactStmt->execute();
        $checkContactResult = $checkContactStmt->get_result();

        if ($checkContactResult->num_rows > 0) {
            // Contact username exists, add it to the Contatti table
            $contactUserId = $checkContactResult->fetch_assoc()['id_utente'];

            $addContactSql = "INSERT INTO Contatti (nomeAssociato, utente_id, utente_contatto_id) VALUES (?, ?, ?)";
            $addContactStmt = $conn->prepare($addContactSql);
            $addContactStmt->bind_param("sii", $newContactName, $_SESSION['id_utente'], $contactUserId);
            $addContactStmt->execute();
            $addContactStmt->close();

            echo "Contatto aggiunto con successo!";

            // Initialize a new chat between the current user and the added contact
            $initializeChatSql = "INSERT INTO Chat (statoChat, utente_id, utente_contatto_id) VALUES (?, ?, ?)";
            $initializeChatStmt = $conn->prepare($initializeChatSql);
            $chatState = "Active";  // You can set the initial chat state as needed
            $initializeChatStmt->bind_param("sii", $chatState, $_SESSION['id_utente'], $contactUserId);
            $initializeChatStmt->execute();
            $initializeChatStmt->close();

            echo "Inizializzazione chat completata!";
        } else {
            echo "Errore: L'username del contatto non esiste.";
        }

        $checkContactStmt->close();
    } catch (Exception $e) {
        echo "Errore durante l'aggiunta del contatto e l'inizializzazione della chat.";
    }
}

$conn->close();
?>
