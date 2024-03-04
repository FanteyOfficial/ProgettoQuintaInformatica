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

document.addEventListener('DOMContentLoaded', () => {
    if (currentChatId == -1) {
        const noChatSelectedElement = document.getElementById('messages');
        noChatSelectedElement.innerHTML = 'Nessuna chat selezionata';
    }
});