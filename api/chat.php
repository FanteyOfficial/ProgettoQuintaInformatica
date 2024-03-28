<?php
    include '../config.php';

    $request_method = $_SERVER['REQUEST_METHOD'];
    $request_uri = explode('/', trim($_SERVER['PATH_INFO'],'/'));

    switch($request_method) {
        case 'GET':
            if (isset($request_uri[1])) {
                getChat($request_uri[1]);
            } else {
                getChats();
            }
            break;
        case 'POST':
            createChat();
            break;
        case 'PUT':
            updateChat($request_uri[1]);
            break;
        case 'DELETE':
            deleteChat($request_uri[1]);
            break;
        default:
            header("HTTP/1.0 405 Method Not Allowed");
            break;
    }

    function getChats() {
        global $conn;
        $sql = "SELECT * FROM chat";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = array();
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        echo json_encode($res);
    }

    function getChat($id) {
        global $conn;
        $sql = "SELECT * FROM chat WHERE id_chat = ?";
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

    function createChat() {
        global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO chat (chat_name, chat_message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $data['chat_name'], $data['chat_message']);
        $stmt->execute();
        echo json_encode(array('message' => 'Chat created successfully'));
    }

    function updateChat($id) {
        global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE chat SET chat_name = ?, chat_message = ? WHERE id_chat = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $data['chat_name'], $data['chat_message'], $id);
        $stmt->execute();
        echo json_encode(array('message' => 'Chat updated successfully'));
    }

    function deleteChat($id) {
        global $conn;
        $sql = "DELETE FROM chat WHERE id_chat = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(array('message' => 'Chat deleted successfully'));
    }
?>