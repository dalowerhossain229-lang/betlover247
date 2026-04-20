<?php
include 'db.php';
header('Content-Type: application/json');

$name = mysqli_real_escape_string($conn, $_POST['fullName'] ?? '');
$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$pass = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

if ($conn->query("INSERT INTO users (full_name, username, password, balance) VALUES ('$name', '$user', '$pass', 0.00)")) {
    echo json_encode(["status" => "success", "message" => "রেজিস্ট্রেশন সফল!"]);
} else {
    echo json_encode(["status" => "error", "message" => "সার্ভার এরর বা ইউজার আইডি ইতিমধ্যে আছে।"]);
}
?>
