<?php include 'config.php'; ?>

<?php
    session_start();

    if (!isset($_SESSION['id_utente']) && isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];
    
        // Check if the token is valid
        $checkTokenSql = "SELECT id_utente FROM Utenti WHERE remember_me_token = ?";
        $checkTokenStmt = $conn->prepare($checkTokenSql);
        $checkTokenStmt->bind_param("s", $token);
        $checkTokenStmt->execute();
        $checkTokenResult = $checkTokenStmt->get_result();
    
        if ($checkTokenResult->num_rows > 0) {
            $userId = $checkTokenResult->fetch_assoc()['id_utente'];
            $_SESSION['id_utente'] = $userId;
        }
    
        $checkTokenStmt->close();
    }

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
        <a href="profile.php">Profilo utente</a>
        <form method="post" action="home.php">
            <input type="text" name="search" placeholder="Cerca" autocomplete="off"/>
            <input type="submit" value="🔍" />
        </form>
    </header>

    <main>
        <div class="users">
            <?php
                $user_id = $_SESSION['id_utente'];

                // get user username
                $sql = "SELECT username FROM Utenti WHERE id_utente = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $username = $row['username'];

                $sql = "SELECT c.id_chat, c.statoChat, c.partecipante1, c.partecipante2, u1.username AS username_partecipante1, u2.username AS username_partecipante2
                        FROM Chat c
                        JOIN Utenti u1 ON c.partecipante1 = u1.id_utente
                        JOIN Utenti u2 ON c.partecipante2 = u2.id_utente
                        WHERE c.partecipante1 = ? OR c.partecipante2 = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $user_id, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    // Determine the username of the other participant
                    $other_username = ($row['username_partecipante1'] == $username) ? $row['username_partecipante2'] : $row['username_partecipante1'];

                    // Display the username and link to the chat
                    echo '<a href="home.php?chat_id=' . $row['id_chat'] . '">' . $other_username . '</a>';
                }
            ?>
        </div>


        <div class="chat-container">
            <?php
                // Check if a specific chat is selected
                $currentChatId = isset($_GET['chat_id']) ? intval($_GET['chat_id']) : 0;

                if ($currentChatId > 0) {
                    // Fetch and display messages for the selected chat
                    $sqlMessages = "SELECT m.utente_id, m.contenuto, m.ora_invio, u.username AS author_username
                                    FROM Messaggi m
                                    JOIN Utenti u ON m.utente_id = u.id_utente
                                    WHERE m.chat_id = ?
                                    ORDER BY m.ora_invio ASC";
                    $stmtMessages = $conn->prepare($sqlMessages);
                    $stmtMessages->bind_param("i", $currentChatId);
                    $stmtMessages->execute();
                    $resultMessages = $stmtMessages->get_result();
            ?>
            <div class="chat">
                <div class="chat-header">
                    <p class="chat-name">Chat <?php echo $currentChatId; ?></p>
                </div>
                <div class="messages">
                    <?php
                        while ($messageRow = $resultMessages->fetch_assoc()) {
                            $messageAuthor = $messageRow['author_username'];
                            $messageContent = $messageRow['contenuto'];
                            $messageTimestamp = $messageRow['ora_invio'];

                            $isCurrentUser = ($messageAuthor === $username);

                            // Message structure based on sender/receiver
                            if ($isCurrentUser) {
                                echo '<div class="msg">
                                        <div class="message-text-sender">
                                            <p class="msg-author">Tu</p>
                                            <p class="msg-content">' . $messageContent . '</p>
                                            <p class="msg-timestamp">' . $messageTimestamp . '</p>
                                        </div>
                                    </div>';
                            } else {
                                echo '<div class="msg">
                                        <div class="message-text-receiver">
                                            <p class="msg-author">' . $messageAuthor . '</p>
                                            <p class="msg-content">' . $messageContent . '</p>
                                            <p class="msg-timestamp">' . $messageTimestamp . '</p>
                                        </div>
                                    </div>';
                            }
                        }
                    ?>
                </div>
                <div class="message-input">
                    <?php
                        // Check if the form is submitted
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
                            try {
                                $messageContent = $_POST['message'];
                                $currentUserId = $_SESSION['id_utente'];

                                // Insert the new message into the database
                                $insertMessageSql = "INSERT INTO Messaggi (utente_id, contenuto, ora_invio, letto, consegnato, chat_id, tipo) 
                                                    VALUES (?, ?, CURRENT_TIMESTAMP, 0, 0, ?, 1)"; // Assuming tipo 1 is a text message
                                $insertMessageStmt = $conn->prepare($insertMessageSql);
                                $insertMessageStmt->bind_param("isi", $currentUserId, strip_tags($messageContent), $currentChatId);
                                $insertMessageStmt->execute();
                                $insertMessageStmt->close();

                                // Redirect to avoid form resubmission on page refresh
                                header("Location: home.php?chat_id=$currentChatId");
                                exit();
                            } catch (Exception $e) {
                                echo "Error sending message: " . $e->getMessage();
                            }
                        }

                        // Fetch and display messages
                        $sqlMessages = "SELECT m.utente_id, m.contenuto, m.ora_invio, u.username
                                        FROM Messaggi m
                                        JOIN Utenti u ON m.utente_id = u.id_utente
                                        WHERE m.chat_id = ?
                                        ORDER BY m.ora_invio ASC";
                        $stmtMessages = $conn->prepare($sqlMessages);
                        $stmtMessages->bind_param("i", $currentChatId);
                        $stmtMessages->execute();
                        $resultMessages = $stmtMessages->get_result();
                    ?>

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
