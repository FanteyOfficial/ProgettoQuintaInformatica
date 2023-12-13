<?php include 'config.php'; ?>

<?php
    session_start();

    if (!isset($_SESSION['id_utente'])) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet" href="./css/home.css" />
    <title>Home</title>
</head>
<body>
    <header>
        <a href="#">Profilo utente</a>
        <form method="post" action="home.php">
            <input type="text" name="search" placeholder="Cerca" />
            <input type="submit" value="🔍" />
        </form>
    </header>

    <main>
        <div class="users">
            <?php
                $user_id = $_SESSION['id_utente'];
                $sql = "SELECT c.id_chat, c.statoChat, u.id_utente, u.username, r.nomeAssociato
                        FROM Chat c
                        LEFT JOIN ConversaIn ci ON c.id_chat = ci.chat_id
                        LEFT JOIN Utenti u ON (u.id_utente = ci.utente_id AND u.id_utente != ?)
                        LEFT JOIN Rubrica r ON u.id_utente = r.utente_id
                        WHERE c.id_chat IS NOT NULL";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $chat_name = ($row['nomeAssociato']) ? $row['nomeAssociato'] : $row['username'] . " (ID: " . $row['id_utente'] . ")";
                    $chat_id = $row['id_chat'];
                    echo '<a href="home.php?chat_id=' . $chat_id . '">' . $chat_name . '</a>';
                }
            ?>
        </div>

        <div class="chat-container">
            <?php
                // Check if a specific chat is selected
                $currentChatId = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : 0;

                if ($currentChatId > 0) {
                    // Fetch and display messages for the selected chat
                    $sqlMessages = "SELECT autore, contenuto, ora_invio FROM Messaggi WHERE chat_id = ? ORDER BY ora_invio";
                    $stmtMessages = $conn->prepare($sqlMessages);
                    $stmtMessages->bind_param("i", $currentChatId);
                    $stmtMessages->execute();
                    $resultMessages = $stmtMessages->get_result();
            ?>
            <div class="chat">
                <div  class="chat-header">
                    <p class="chat-name">Chat <?php echo $currentChatId; ?></p>
                </div>
                <div class="messages">
                    <?php
                        while ($messageRow = $resultMessages->fetch_assoc()) {
                            $messageAuthor = $messageRow['autore'];
                            $messageContent = $messageRow['contenuto'];
                            $messageTimestamp = $messageRow['ora_invio'];

                            // Assuming you have a way to identify whether the message was sent by the current user
                            $isCurrentUser = ($messageAuthor === $row['username']); // Replace with your actual logic

                            // Message structure based on sender/receiver
                            if ($isCurrentUser) {
                                echo '<div class="msg">
                                        <div class="message-text-sender">
                                            <p class="msg-author">Tu</p>
                                            <p class="msg-content">' . $messageContent . '</p>
                                        </div>
                                        </div>';
                            } else {
                                echo '<div class="msg">
                                        <div class="message-text-receiver">
                                            <p class="msg-author">' . $messageAuthor . '</p>
                                            <p class="msg-content">' . $messageContent . '</p>
                                        </div>
                                        </div>';
                            }
                        }
                    ?>
                </div>
                <div class="message-input">
                    <form method="post" action="home.php?chat_id=<?php echo $currentChatId; ?>" >
                        <input type="text" name="message" placeholder="Scrivi un messaggio" />
                        <input type="submit" value="Invia" />
                    </form>
                </div>
            </div>
            <?php
                } else {
                    // Display a default message or instructions when no chat is selected
                    echo '<p>Select a chat to view messages.</p>';
                }
            ?>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>

