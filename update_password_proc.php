<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// সেশন চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
$old = trim($_POST['old_pass']);
$new = trim($_POST['new_pass']);

// ১. ডাটাবেস থেকে তথ্য আনা
$res = $conn->query("SELECT password FROM users WHERE username = '$user'");
$u_data = $res->fetch_assoc();

// ২. পাসওয়ার্ড চেক (খুবই সতর্কভাবে)
if (!$u_data || $old != $u_data['password']) {
    echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ড ভুল!"]);
    exit;
}

// ৩. পাসওয়ার্ড আপডেট
if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$user'")) {
    echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে আপডেট হয়েছে!"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
