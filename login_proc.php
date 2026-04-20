<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

$res = $conn->query("SELECT * FROM users WHERE username = '$user'");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
        $_SESSION['user_id'] = $row['username'];
        $_SESSION['balance'] = $row['balance'];
        echo json_encode(["status" => "success", "message" => "লগইন সফল!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ভুল পাসওয়ার্ড!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ইউজার আইডি পাওয়া যায়নি!"]);
}
?>

