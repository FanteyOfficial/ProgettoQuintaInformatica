<?php include 'config.php'; ?>

<?php
    session_start();

    if (!isset($_SESSION['id_utente']) && isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];
    
        // Check if the token is valid
        $checkTokenSql = "SELECT id_utente FROM Utenti WHERE remember_me_token = ?";
        $checkTokenStmt = $conn->prepare($checkTokenSql);
        $checkTokenStmt->bind_param("s", $token);
        $checkTokenStmt->execute();
        $checkTokenResult = $checkTokenStmt->get_result();
    
        if ($checkTokenResult->num_rows > 0) {
            $userId = $checkTokenResult->fetch_assoc()['id_utente'];
            $_SESSION['id_utente'] = $userId;
        }
    
        $checkTokenStmt->close();
    }

    if (!isset($_SESSION['id_utente'])) {
        header("Location: login.php");
        exit();
    }
?>

<?php
    $resultMessage = "";

    // Update user data if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            <input type="submit" value="Salva modifiche">
        </form>

        <p><?php echo $resultMessage; ?></p>

        <a href="home.php">Home</a>
        <br>
        <a href="logout.php">Logout</a>
        <br>
        <a href="delete_account.php">Elimina account</a>
    </body>
</html>