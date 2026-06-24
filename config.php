<?php

$host = "localhost";
$db = "gaton_im102";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn = new mysqli($host, $username, $password, $db);
    if ($conn->connect_error) {
        throw new Exception('MySQLi connection failed: ' . $conn->connect_error);
    }
    $conn->set_charset('utf8');

} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}