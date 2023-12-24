<?php
include 'config.php';

session_start();

if (!isset($_SESSION['id_utente'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Deactivate the account
    $userId = $_SESSION['id_utente'];

    // Update the 'stato' column to indicate the account is deactivated
    $deactivateUserSql = "UPDATE Utenti SET stato = 3 WHERE id_utente = ?";
    $deactivateUserStmt = $conn->prepare($deactivateUserSql);
    $deactivateUserStmt->bind_param("i", $userId);
    $deactivateUserStmt->execute();
    $deactivateUserStmt->close();

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
        <title>Deactivate account</title>
    </head>
    <body>
        <h1>Deactivate account</h1>
        <p>Sei sicuro di voler disattivare il tuo account?</p>
        <form method="post" action="">
            <input type="submit" value="Conferma disattivazione">
        </form>
        <br>
        <a href="profile.php">Annulla</a>
    </body>
</html>
