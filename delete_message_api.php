<?php
    include 'config.php';

    $message_id = $_POST['message_id'];
    
    if ($message_id > 0) {
        // Fetch and display messages for the selected chat
        $sqlMessages = "DELETE FROM Messaggi WHERE id_messaggio = ?";
        $stmtMessages = $conn->prepare($sqlMessages);
        $stmtMessages->bind_param("i", $message_id);
        $stmtMessages->execute();
        $resultMessages = $stmtMessages->get_result();

        $rows = array('success' => true);
    }
    else { 
        $rows = array('success' => false);
    }
    
    echo json_encode($rows);
?>