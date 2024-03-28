<?php
    include '../config.php';

    $request_method = $_SERVER['REQUEST_METHOD'];
    $request_uri = explode('/', trim($_SERVER['PATH_INFO'],'/'));

    switch($request_method) {
        case 'GET':
            if (count($request_uri) >= 2 && $request_uri[1] != '') {
                if ($request_uri[2] == 'messaggi') {
                    getMessages($request_uri[1]);
                } elseif ($request_uri[2] == 'chat') {
                    getChat($request_uri[1]);
                }
            } elseif (count($request_uri) == 2 && $request_uri[1] == '') {
                getUser($request_uri[0]);
            } else {
                getUsers();
            }
            break;
        case 'POST':
            createUser();
            break;
        case 'PUT':
            updateUser($request_uri[0]);
            break;
        case 'DELETE':
            deleteUser($request_uri[0]);
            break;
        default:
            header("HTTP/1.0 405 Method Not Allowed");
            break;
    }

    function getUsers() {
        global $conn;
        $sql = "SELECT * FROM utenti";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $res = array();
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;
        }
        echo json_encode($res);
    }

    function getUser($id) {
        global $conn;
        $sql = "SELECT * FROM utenti WHERE id_utente = ?";
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

    function createUser() {
        global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO utenti (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $data['username'], $data['password']);
        $stmt->execute();
        echo json_encode(array('message' => 'User created successfully'));
    }

    function updateUser($id) {
        global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE utenti SET username = ?, password = ? WHERE id_utente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $data['username'], $data['password'], $id);
        $stmt->execute();
        echo json_encode(array('message' => 'User updated successfully'));
    }

    function deleteUser($id) {
        global $conn;
        $sql = "DELETE FROM utenti WHERE id_utente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(array('message' => 'User deleted successfully'));
    }

    function getMessages($id) {
        global $conn;
        $sql = "SELECT * FROM messaggi WHERE user_id = ?";
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

    function getChat($id) {
        global $conn;
        $sql = "SELECT * FROM chat WHERE user_id = ?";
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
?>