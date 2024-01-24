<?php
    include 'config.php';

    $chat_id = $_GET["c"];

    if ($chat_id > 0) {
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
    }

    $rows = array();
    
    while ($row = $resultMessages->fetch_assoc()) {
        // save the chat id and the other username
        $rows[] = array(
            'utente_id' => $row['utente_id'], 
            'contenuto' => $row['contenuto'],
            'ora_invio' => $row['ora_invio'],
            'username' => $row['author_username']
        );
    }
    
    echo json_encode($rows);
?>