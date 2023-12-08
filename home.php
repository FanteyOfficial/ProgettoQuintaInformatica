<?php include 'config.php'; ?>

<?php
    session_start();

    if (!isset ($_SESSION['id_utente'])) {
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Welcome to the Home Page</h1>
    <p>Hello, <?php echo $_SESSION['id_utente']; ?>!</p>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php $conn->close(); ?>
