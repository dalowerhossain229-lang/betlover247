<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$user = $_SESSION['user_id'];
$old = $_POST['old_pass'];
$new = $_POST['new_pass'];

// পুরাতন পাসওয়ার্ড চেক
$res = $conn->query("SELECT password FROM users WHERE username = '$user'");
$u_data = $res->fetch_assoc();

if ($old !== $u_data['password']) {
    echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ড ভুল!"]);
    exit;
}

// নতুন পাসওয়ার্ড আপডেট
if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$user'")) {
    echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
}
?>
