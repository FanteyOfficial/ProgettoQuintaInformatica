<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $checkEmailQuery = "SELECT id_utente, username FROM Utenti WHERE mail = ?";
    $stmtCheckEmail = $conn->prepare($checkEmailQuery);
    $stmtCheckEmail->bind_param("s", $email);
    $stmtCheckEmail->execute();
    $stmtCheckEmail->store_result();

    if ($stmtCheckEmail->num_rows > 0) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32));

        // Store the token and its expiration time in the database
        $userId = $stmtCheckEmail->fetch_assoc()['id_utente'];
        $expireTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $updateTokenQuery = "UPDATE Utenti SET reset_token = ?, reset_token_expires = ? WHERE id_utente = ?";
        $stmtUpdateToken = $conn->prepare($updateTokenQuery);
        $stmtUpdateToken->bind_param("ssi", $token, $expireTime, $userId);
        $stmtUpdateToken->execute();
        $stmtUpdateToken->close();

        // Send a password reset email to the user
        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Dear user,\n\nPlease click on the following link to reset your password:\n$resetLink\n\nIf you didn't request this, please ignore this email.";
        $headers = "From: webmaster@yourdomain.com";

        mail($email, $subject, $message, $headers);

        echo "An email with instructions to reset your password has been sent to your email address.";
    } else {
        echo "Email not found in our records.";
    }

    $stmtCheckEmail->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
</head>
<body>
    <h1>Password Recovery</h1>
    <form method="post" action="password_recovery.php">
        <label for="email">Email:</label>
        <input type="text" name="email" required>
        <br>
        <input type="submit" value="Recover Password">
    </form>

    <p>Remember your password? <a href="login.php">Login</a></p>
</body>
</html>
