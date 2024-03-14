let createDeleteButton = (messageId) => {
    const deleteButton = document.createElement('button');
    deleteButton.classList.add('delete-button');
    deleteButton.innerText = '🗑️';
    deleteButton.addEventListener('click', () => {
        deleteMessage(messageId);
    });

    return deleteButton;
}

function getMessages(chatId, other_username) {
    event.preventDefault();

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../get_messages_api.php', true);
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

                // Display the delete button if the message is from the current user
                if (msg.utente_id == userId) {
                    messageElement.appendChild(createDeleteButton(msg.id_messaggio));
                }
                
                messagesContainer.appendChild(messageElement);
            });

            // Scroll to the bottom of the messages container
            // messagesContainer.scrollTop = messagesContainer.scrollHeight;

        } else {
            const noMessagesElement = document.getElementById('messages');
            noMessagesElement.innerHTML = '<p class="no-messages">Non ci sono messaggi</p>';
        }

        // show the message input if it's not already shown
        if (!document.getElementById('message-input').childElementCount){
                const messageInput = document.getElementById('message-input');
                formElement = document.createElement('form');
                formElement.method = 'post';
                formElement.action = '';
                formElement.autocomplete = 'off';
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
    };
}

function deleteMessage(messageId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../delete_message_api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('message_id=' + messageId);

    xhr.onload = () => {
        if (xhr.status !== 200) {
            console.error('Error while deleting message. Status:', xhr.status);
            return;
        }

        const response = JSON.parse(xhr.responseText);

        if (response.success) {
            // Reload the messages
            const other_username = document.querySelector('.chat-name').innerText;
            getMessages(response.message_id, other_username);
        } else {
            console.error('Error in server response:', response.error);
            // Handle the error or provide a user-friendly message
        }
    };

    xhr.onerror = () => {
        console.error('Error while deleting message');
    };
}

function sendMessage(chatId, userId) {
    event.preventDefault();
    
    const message = document.getElementById('message').value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../send_message_api.php', true);
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
            const other_username = document.querySelector('.chat-name').innerText;

            getMessages(response.chat_id, other_username);
        }
    };

    xhr.onerror = () => {
        console.error('Error while sending message');
    };
}
