<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'chat_app_test';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
