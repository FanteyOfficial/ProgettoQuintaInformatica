<?php
    include "config.php";
    
    session_start();

    // Clear the remember me token
    if (isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];

        // Clear the token from the database
        $clearTokenSql = "UPDATE Utenti SET remember_me_token = NULL WHERE remember_me_token = ?";
        $clearTokenStmt = $conn->prepare($clearTokenSql);
        $clearTokenStmt->bind_param("s", $token);
        $clearTokenStmt->execute();
        $clearTokenStmt->close();

        // Expire the remember me cookie
        setcookie("remember_me", "", null, "/");
    }

    $_SESSION = array();

    session_destroy();

    header("Location: login.php");
    exit();
?>