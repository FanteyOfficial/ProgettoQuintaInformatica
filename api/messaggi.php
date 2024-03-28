<?php
    include '../config.php';

    $request_method = $_SERVER['REQUEST_METHOD'];
    $request_uri = explode('/', trim($_SERVER['PATH_INFO'],'/'));

    switch($request_method) {
        case 'GET':
            if (isset($request_uri[1])) {
                getMessage($request_uri[1]);
            } else {
                getMessages();
            }
            break;
        case 'POST':
            createMessage();
            break;
        case 'PUT':
            updateMessage($request_uri[1]);
            break;
        case 'DELETE':
            deleteMessage($request_uri[1]);
            break;
        default:
            header("HTTP/1.0 405 Method Not Allowed");
            break;
    }

    function getMessages() {
        global $conn;
        $sql = "SELECT * FROM messaggi";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = array();
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        echo json_encode($res);
    }

    function getMessage($id) {
        global $conn;
        $sql = "SELECT * FROM messaggi WHERE id_messaggio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = array();
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        echo json_encode($res);
    }

    function createMessage() {
        global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO messaggi (message_content) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $data['message_content']);
        $stmt->execute();
        echo json_encode(array('message' => 'Message created successfully'));
    }

    function updateMessage($id) {
        global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE messaggi SET message_content = ? WHERE id_messaggio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $data['message_content'], $id);
        $stmt->execute();
        echo json_encode(array('message' => 'Message updated successfully'));
    }

    function deleteMessage($id) {
        global $conn;
        $sql = "DELETE FROM messaggi WHERE id_messaggio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(array('message' => 'Message deleted successfully'));
    }
?>