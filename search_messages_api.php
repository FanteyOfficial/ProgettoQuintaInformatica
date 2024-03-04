<?php
    include 'config.php';

    $chat_id = $_POST["chat_id"];
    $search_value = "%" . strip_tags($_POST["search_value"]) . "%";

    if ($chat_id > 0) {
        // Fetch and display messages for the selected chat
        $sqlMessages = "SELECT m.id_messaggio, m.utente_id, m.contenuto, m.ora_invio, u.username AS author_username
                        FROM Messaggi m
                        JOIN Utenti u ON m.utente_id = u.id_utente
                        WHERE m.chat_id = ? AND m.contenuto LIKE ?
                        ORDER BY m.ora_invio ASC";
        $stmtMessages = $conn->prepare($sqlMessages);
        $stmtMessages->bind_param("is", $chat_id, $search_value);
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
                'username' => $row['author_username'],
                'id_messaggio' => $row['id_messaggio']
        );
    }
    
    echo json_encode($rows);
?>