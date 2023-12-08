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
        $hashedPasswordAttempt = password_hash($password . $salt, PASSWORD_DEFAULT);

        if (password_verify($hashedPasswordAttempt, $storedPassword)) {
            $_SESSION['id_utente'] = $row['id_utente'];
            header("Location: home.php");
            exit();
        } else {
            echo "Invalid email or password1.";
        }
    } else {
        echo "Invalid email or password.";
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
</head>
<body>
    <h1>Login</h1>
    <form method="post" action="login.php">
        <label for="mail">Email:</label>
        <input type="text" name="mail" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Login">

        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</body>
</html>
