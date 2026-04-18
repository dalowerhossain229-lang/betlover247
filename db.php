<?php
$host = "sql308.infinityfree.com";
$user = "if0_41487425";
$pass = "Aayan1995";
$db   = "if0_41487425_db_gamer";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
