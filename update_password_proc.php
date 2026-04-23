<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "সেশন আউট! আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
$new = trim($_POST['new_pass'] ?? ''); // আমরা শুধু নতুন পাসওয়ার্ডটি নেব

if (empty($new)) {
    echo json_encode(["status" => "error", "message" => "নতুন পাসওয়ার্ডটি লিখুন!"]);
    exit;
}

// পুরাতন পাসওয়ার্ড চেক না করেই সরাসরি আপডেট (সবার জন্য কাজ করবে)
$update_query = "UPDATE users SET password = '$new' WHERE username = '$user' OR id = '$user'";

if ($conn->query($update_query)) {
    echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে রিসেট ও পরিবর্তন হয়েছে!"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
}
?>
