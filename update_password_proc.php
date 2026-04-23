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
$new = trim($_POST['new_pass'] ?? '');

if (empty($new)) {
    echo json_encode(["status" => "error", "message" => "নতুন পাসওয়ার্ডটি লিখুন!"]);
    exit;
}

// ২. সরাসরি আপডেট কমান্ড (সবচেয়ে সহজ ও শক্তিশালী পদ্ধতি)
$update_sql = "UPDATE users SET password = '$new' WHERE username = '$user'";

if ($conn->query($update_sql)) {
    echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
} else {
    // ৩. কোনো সমস্যা হলে আসল এররটি জানানো
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
