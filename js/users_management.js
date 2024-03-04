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
            userElement.id = 'user-link';
            userElement.href = 'home.php?chat_id=' + user.id_chat;
            userElement.addEventListener('click', () => {
                getMessages(user.id_chat, user.other_username);
            });
            userElement.innerText = user.other_username;
            usersContainer.appendChild(userElement);
        });
    };
}