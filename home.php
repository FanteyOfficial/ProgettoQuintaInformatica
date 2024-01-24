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
        <form method="post" action="home.php" autocomplete="off">
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <input type="text" name="search" placeholder="Cerca" id="searchBar" />
            <input type="submit" value="🔍" id="searchBTN" />
        </form>

        <script>
            const searchInput = document.getElementById('searchBar');
            const searchBTN = document.getElementById('searchBTN');

            searchInput.addEventListener('input', () => {
                const usernameToSearch = searchInput.value;
                getUsers(usernameToSearch);
            });

            searchBTN.addEventListener('click', (e) => {
                e.preventDefault();
                const usernameToSearch = searchInput.value;
                getUsers(usernameToSearch);
            });
        </script>
    </header>

    <main>
        <div class="users" id="users">
            <?php
                $user_id = $_SESSION['id_utente'];

                echo '<script>const userId = "' . $user_id . '";</script>';
            ?>

            <script>
                function getUsers(usernameToSearch = '') {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'get_users_chats_api.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    if (usernameToSearch !== '') {
                        xhr.send('user_id=' + userId + '&usernameToSearch=' + usernameToSearch);
                    } else {
                        xhr.send('user_id=' + userId + '&usernameToSearch=' + '');
                    }
                    
                    xhr.onload = () => {
                        if (xhr.status !== 200) {
                            console.error('Error while fetching users');
                            return;
                        }
                        // console.log(xhr.responseText);

                        const users = JSON.parse(xhr.responseText);
                        const usersContainer = document.getElementById('users');

                        // Clear the users container
                        usersContainer.innerHTML = '';

                        // Display each user
                        users.forEach(user => {
                            const userElement = document.createElement('a');
                            userElement.classList.add('user-link');
                            userElement.href = 'home.php?chat_id=' + user.id_chat;
                            userElement.addEventListener('click', () => {
                                getMessages(user.id_chat, user.other_username);
                            });
                            userElement.innerText = user.other_username;
                            usersContainer.appendChild(userElement);
                        });
                    };
                }

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
            <script>
                function getMessages(chatId, other_username="") {
                    event.preventDefault();

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'get_messages_api.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.send('chat_id=' + chatId);
                    
                    xhr.onload = () => {
                        if (xhr.status !== 200) {
                            console.error('Error while fetching messages');
                            return;
                        }
                        // console.log(xhr.responseText);

                        const messages = JSON.parse(xhr.responseText);
                        const messagesContainer = document.getElementById('messages');

                        // Clear the messages container
                        messagesContainer.innerHTML = '';

                        // Display the chat name
                        const chatNameElement = document.querySelector('.chat-name');
                        chatNameElement.innerText = other_username;

                        // Display each message if there are any
                        if (messages.length > 0) {
                            messages.forEach(msg => {
                                const messageElement = document.createElement('div');
                                messageElement.classList.add('message');
                                messageElement.innerHTML = `
                                    <p class="message-author">${msg.username}</p>
                                    <p class="message-content">${msg.contenuto}</p>
                                    <p class="message-time">${msg.ora_invio}</p>
                                `;
                                messagesContainer.appendChild(messageElement);
                            });

                            // Scroll to the bottom of the messages container
                            // messagesContainer.scrollTop = messagesContainer.scrollHeight;

                            // show the message input if it's not already shown
                            if (document.getElementById('message-input').childElementCount == 1){
                                const messageInput = document.getElementById('message-input');
                                formElement = document.createElement('form');
                                formElement.method = 'post';
                                formElement.action = '';
                                inputElement = document.createElement('input');
                                inputElement.type = 'text';
                                inputElement.id = 'message';
                                inputElement.placeholder = 'Scrivi un messaggio';
                                submitElement = document.createElement('input');
                                submitElement.type = 'submit';
                                submitElement.value = 'Invia';
                                submitElement.addEventListener('click', () => {
                                    sendMessage(chatId, userId);
                                });
                                formElement.appendChild(inputElement);
                                formElement.appendChild(submitElement);
                                messageInput.appendChild(formElement);
                            }
                        } else {
                            const noMessagesElement = document.getElementById('messages');
                            noMessagesElement.innerHTML = '<p class="no-messages">Non ci sono messaggi</p>';
                        }
                    };
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const noMessagesElement = document.getElementById('messages');
                    noMessagesElement.innerHTML = 'Nessuna chat selezionata';
                });
            </script>
            <div class="chat">
                <div class="chat-header">
                    <p class="chat-name"></p>
                </div>
                <div class="messages" id="messages"></div>
                <div class="message-input" id="message-input">
                    <script>
                        function sendMessage(chatId, userId) {
                            event.preventDefault();
                            
                            const message = document.getElementById('message').value;
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', 'send_message_api.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.send('chat_id=' + chatId + '&user_id=' + userId + '&message=' + message);

                            xhr.onload = () => {
                                if (xhr.status !== 200) {
                                    console.error('Error while sending message');
                                    return;
                                }
                                // console.log(xhr.responseText);

                                const response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    // Clear the message input
                                    document.getElementById('message').value = '';

                                    // Reload the messages
                                    getMessages(chatId);
                                }
                            };

                            xhr.onerror = () => {
                                console.error('Error while sending message');
                            };
                        }
                    </script>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>
