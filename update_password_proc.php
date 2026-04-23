<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
// trim ব্যবহার করা হয়েছে যাতে বাড়তি স্পেস এরর না দেয়
$old = trim($_POST['old_pass']); 
$new = trim($_POST['new_pass']);

// ২. পুরাতন পাসওয়ার্ড চেক
$res = $conn->query("SELECT password FROM users WHERE username = '$user'");
$u_data = $res->fetch_assoc();

// ৩. পাসওয়ার্ড যাচাই (সব ভার্সনের জন্য নিরাপদ)
if (!$u_data || $old !== $u_data['password']) {
    echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ড ভুল!"]);
    exit;
}

// ৪. নতুন পাসওয়ার্ড আপডেট
if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$user'")) {
    echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
}
?>
