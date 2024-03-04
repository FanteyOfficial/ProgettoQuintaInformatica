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

    <script src="./js/messages_management.js"></script>
    <script src="./js/users_management.js"></script>
    <script src="./js/home_events.js"></script>
</head>
<body>
    <header>
        <a href="profile.php">Profilo utente</a>
        <form method="post" action="home.php" autocomplete="off">
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <input type="text" name="search" placeholder="Cerca" id="searchBar" />
            <input type="submit" value="🔍" id="searchBTN" />
        </form>
    </header>

    <main>
        <div class="users" id="users">
            <?php
                $user_id = $_SESSION['id_utente'];

                echo '<script>const userId = "' . $user_id . '";</script>';
            ?>

            <script>
                // run script on page load
                getUsers();
            </script>
        </div>


        <div class="chat-container">
            <?php
                // check if the chat is selected and get the chat id with GET
                if (isset($_GET['chat_id'])) {
                    $currentChatId = $_GET['chat_id'];
                } else {
                    $currentChatId = -1;
                }

                echo '<script>const currentChatId = "' . $currentChatId . '";</script>';
            ?>
            <div class="chat">
                <div class="chat-header">
                    <p class="chat-name"></p>
                </div>
                <div class="messages" id="messages"></div>
                <div class="message-input" id="message-input"></div>
            </div>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>
