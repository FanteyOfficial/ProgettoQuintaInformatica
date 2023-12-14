<?php
include 'config.php';

session_start();

if (isset($_SESSION['id_utente'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    $sql = "SELECT id_utente, password, salt FROM utenti WHERE mail = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $mail);
    
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $storedPassword = $row['password'];
        $salt = $row['salt'];
        $userEnteredPassword = $_POST['password'];

        if (password_verify($userEnteredPassword . $salt, $storedPassword)) {
            // Generate a random remember me token
            $rememberMeToken = bin2hex(random_bytes(32));

            // Store the token in the database
            $updateTokenSql = "UPDATE Utenti SET remember_me_token = ? WHERE id_utente = ?";
            $updateTokenStmt = $conn->prepare($updateTokenSql);
            $updateTokenStmt->bind_param("si", $rememberMeToken, $row['id_utente']);
            $updateTokenStmt->execute();
            $updateTokenStmt->close();

            // Set the remember me cookie
            setcookie("remember_me", $rememberMeToken, time() + (60), "/"); // Cookie expires in 30 days

            $_SESSION['id_utente'] = $row['id_utente'];
            header("Location: home.php");
            exit();
        } else {
            $failed = "Invalid email or password.";
        }
    } else {
        $failed = "Non ho trovato nessun account con questa mail.";
    }

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html5>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link type="text/css" rel="stylesheet" href="css/shared-login.css" />
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="post" action="login.php">
            <!-- <label for="mail">Email:</label> -->
            <input class="input__field" type="text" name="mail" placeholder="Email" required>
            <!-- <label for="password">Password:</label> -->
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>

        <span class="exceptions">
            <?php 
                if (isset($failed)) echo $failed; 
            ?>
        </span>

        <span class="divider"></span>
        <a href="register.php">Register</a>
    </div>
</body>
</html>
