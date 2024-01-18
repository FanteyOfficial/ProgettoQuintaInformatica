<?php
    include 'config.php';

    $user_id = $_POST['user_id'];
    $usernameToSearch = "";
    if (strip_tags($_POST['usernameToSearch']) != "") {
        $usernameToSearch = strip_tags($_POST['usernameToSearch']);
    }

    // get user username
    $sql = "SELECT username FROM Utenti WHERE id_utente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $username = $row['username'];

    if ($usernameToSearch != "" && $usernameToSearch != $username) {
        // get all the chats of the user with the username searched
        $sql = "SELECT c.id_chat, c.statoChat, c.partecipante1, c.partecipante2, u1.username AS username_partecipante1, u2.username AS username_partecipante2
                FROM Chat c
                JOIN Utenti u1 ON c.partecipante1 = u1.id_utente
                JOIN Utenti u2 ON c.partecipante2 = u2.id_utente
                WHERE (c.partecipante1 = ? OR c.partecipante2 = ?) AND c.statoChat = 1 AND (u1.username LIKE ? OR u2.username LIKE ?)";

        $us = '%' . $usernameToSearch . '%';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $user_id, $user_id, $us, $us);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    else {
        $sql = "SELECT c.id_chat, c.statoChat, c.partecipante1, c.partecipante2, u1.username AS username_partecipante1, u2.username AS username_partecipante2
                FROM Chat c
                JOIN Utenti u1 ON c.partecipante1 = u1.id_utente
                JOIN Utenti u2 ON c.partecipante2 = u2.id_utente
                WHERE (c.partecipante1 = ? OR c.partecipante2 = ?) AND c.statoChat = 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    // output data of each row
    $rows = array();
    
    while ($row = $result->fetch_assoc()) {
        // Determine the username of the other participant
        $other_username = ($row['username_partecipante1'] == $username) ? $row['username_partecipante2'] : $row['username_partecipante1'];
        
        // check if the other username contains the letters searched
        if ($usernameToSearch != "" && !strpos($other_username, $usernameToSearch) === false) {
            continue;
        }

        // check if the other username contains the letters searched (not case insensitive)
        if ($usernameToSearch != "" && stripos($other_username, $usernameToSearch) === false) {
            continue;
        }

        // save the chat id and the other username
        $rows[] = array('id_chat' => $row['id_chat'], 'other_username' => $other_username);
    }
    
    echo json_encode($rows);
?>