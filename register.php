<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $failed = "Invalid email format.";
        exit();
    }

    if (preg_match('/\s/', $username)) {
        $failed = "Username cannot contain spaces.";
        exit();
    }

    $checkEmailQuery = "SELECT id_utente FROM Utenti WHERE mail = ?";
    $stmtCheckEmail = $conn->prepare($checkEmailQuery);
    $stmtCheckEmail->bind_param("s", $mail);
    $stmtCheckEmail->execute();
    $stmtCheckEmail->store_result();

    if ($stmtCheckEmail->num_rows > 0) {
        $failed = "Email already exists. Please use a different email address.";
        exit();
    }

    $salt = bin2hex(random_bytes(16));

    $hashedPassword = password_hash($password . $salt, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Utenti (mail, nome, cognome, username, stato, ultimo_accesso, password, salt) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
    $stmt = $conn->prepare($sql);

    $stato = 1;

    $stmt->bind_param("ssssiss", $mail, $nome, $cognome, $username, $stato, $hashedPassword, $salt);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $failed = "Error in registration: " . $stmt->error;
    }

    $stmt->close();
    $stmtCheckEmail->close();
}

$conn->close();
?>

<!DOCTYPE html5>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="css/shared-login.css" />
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form method="post" action="register.php">
            <!-- <label for="mail">Email:</label> -->
            <input type="text" name="mail" placeholder="Email" required>
            <!-- <label for="nome">Nome:</label> -->
            <input type="text" name="nome" placeholder="Nome" required>
            <!-- <label for="cognome">Cognome:</label> -->
            <input type="text" name="cognome" placeholder="Cognome" required>
            <!-- <label for="username">Username:</label> -->
            <input type="text" name="username" placeholder="Username" required>
            <!-- <label for="password">Password:</label> -->
            <input type="password" name="password" placeholder="Password" required>

            <input type="submit" value="Register">
        </form>

        <span class="exceptions">
            <?php 
                if (isset($failed)) echo $failed; 
            ?>
        </span>

        <span class="divider"></span>
        <a href="login.php">Login</a>
    </div>
</body>
</html>
