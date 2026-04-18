<?php
include 'db.php';
$name = $_GET['name'] ?? '';
// গেমের নাম অনুযায়ী লিঙ্ক খুঁজে আনা
$res = $conn->query("SELECT link FROM games WHERE name='$name'");
$data = $res->fetch_assoc();
echo json_encode(["link" => $data['link'] ?? '']);
?>
