<?php
include 'config.php';

session_start();

if (!isset($_SESSION['id_utente'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perform account deletion
    $userId = $_SESSION['id_utente'];

    // Delete entries from Contatti table
    $deleteRubricaSql = "DELETE FROM Contatti WHERE utente_id = ?";
    $deleteRubricaStmt = $conn->prepare($deleteRubricaSql);
    $deleteRubricaStmt->bind_param("i", $userId);
    $deleteRubricaStmt->execute();
    $deleteRubricaStmt->close();

    // Delete entries from Utenti table
    $deleteUtentiSql = "DELETE FROM Utenti WHERE id_utente = ?";
    $deleteUtentiStmt = $conn->prepare($deleteUtentiSql);
    $deleteUtentiStmt->bind_param("i", $userId);
    $deleteUtentiStmt->execute();
    $deleteUtentiStmt->close();

    // Clear remember me token and expire the remember me cookie
    if (isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];

        // Clear the token from the database
        $clearTokenSql = "UPDATE Utenti SET remember_me_token = NULL WHERE remember_me_token = ?";
        $clearTokenStmt = $conn->prepare($clearTokenSql);
        $clearTokenStmt->bind_param("s", $token);
        $clearTokenStmt->execute();
        $clearTokenStmt->close();

        // Expire the remember me cookie
        setcookie("remember_me", "", time() - 3600, "/");
    }

    // Clear session and redirect to login
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html5>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Elimina account</title>
    </head>
    <body>
        <h1>Elimina account</h1>
        <p>Sei sicuro di voler eliminare il tuo account?</p>
        <form method="post" action="">
            <input type="submit" value="Conferma eliminazione">
        </form>
        <br>
        <a href="profile.php">Annulla</a>
    </body>
</html>
