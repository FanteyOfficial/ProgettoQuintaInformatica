<?php include 'config.php'; ?>

<?php
    session_start();

    if (!isset ($_SESSION['id_utente'])) {
        header("Location: login.php");
    }
?>

<?php
    $sql = "SELECT * FROM Utenti WHERE id_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['id_utente']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
?>

<!DOCTYPE html5>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <!-- <h1>Welcome to the Home Page</h1> -->
    <!-- <p>Hello, <?php //echo $row['username']; ?>!</p>
    <a href="logout.php">Logout</a> -->

    <header>
        <a href="">Profilo utente</a>
        <form method="post" action="home.php" >
            <input type="text" name="search" placeholder="Cerca" />
            <input type="submit" value="🔍" />
        </form>
    </header>
    <main>
        <div class="users">

        </div>
        <div class="chat-container">
            <div class="chat">
                <div class="chat-header">
                    <p>

                </div>
                <div class="messages">
                    <div class="msg">
                        <div class="message-text-sender">
                            <p class="msg-author">Tu</p>
                            <p class="msg-content">Ciao sono il mittente!</p>
                        </div>
                        <div class="message-text-receiver">
                            <p class="msg-author">Pinuccio</p>
                            <p class="msg-content">Ciao sono il destinatario!</p>
                        </div>
                    </div>
                </div>
                <div class="message-input">
                    <form method="post" action="home.php" >
                        <input type="text" name="message" placeholder="Scrivi un messaggio" />
                        <input type="submit" value="Invia" />
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>
