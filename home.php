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
    <?php
        $sql = "SELECT * FROM Utenti WHERE id_utente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['id_utente']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    ?>
    <p>Hello, <?php echo $row['username']; ?>!</p>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php $conn->close(); ?>
