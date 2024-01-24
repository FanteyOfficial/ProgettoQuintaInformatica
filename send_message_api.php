<?php
    include 'config.php';

    $chat_id = $_POST['chat_id'];
    $user_id = $_POST['user_id'];
    $message = strip_tags($_POST['message']);

    if ($chat_id > 0) {
        // Fetch and display messages for the selected chat
        $sqlMessages = "INSERT INTO Messaggi (utente_id, contenuto, ora_invio, letto, consegnato, chat_id, tipo) 
                        VALUES (?, ?, CURRENT_TIMESTAMP, 0, 0, ?, 1)";
        $stmtMessages = $conn->prepare($sqlMessages);
        $stmtMessages->bind_param("isi", $user_id, $message, $chat_id);
        $stmtMessages->execute();
        $resultMessages = $stmtMessages->get_result();

        $rows = array('success' => true);
    }
    else {
        $rows = array('success' => false);
    }

    echo json_encode($rows);
?>