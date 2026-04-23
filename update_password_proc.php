<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. সেশন ভেরিয়েবল চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "সেশন পাওয়া যায়নি! দয়া করে আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
$old = trim($_POST['old_pass']);
$new = trim($_POST['new_pass']);

// ২. ডাটাবেস থেকে পাসওয়ার্ড এবং ইউজার ভেরিফাই করা
$query = "SELECT password FROM users WHERE username = '$user' OR id = '$user'";
$res = $conn->query($query);

if ($res && $res->num_rows > 0) {
    $u_data = $res->fetch_assoc();
    $db_pass = trim($u_data['password']);

    // ৩. পাসওয়ার্ড মেলাবো (Strict comparison সরিয়ে দেওয়া হয়েছে)
    if ($old == $db_pass) {
        // ৪. নতুন পাসওয়ার্ড আপডেট
        if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$user' OR id = '$user'")) {
            echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ডাটাবেস আপডেট এরর: " . $conn->error]);
        }
    } else {
        // ভুল পাসওয়ার্ডের ক্ষেত্রে ডিবাগিং তথ্য (ঐচ্ছিক)
        echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ড মিলছে না।"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেসে ইউজার খুঁজে পাওয়া যায়নি।"]);
}
?>
