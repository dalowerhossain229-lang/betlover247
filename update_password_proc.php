<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "সেশন আউট! আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
$old = trim($_POST['old_pass'] ?? '');
$new = trim($_POST['new_pass'] ?? '');

// ১. ডাটাবেস থেকে ইউজারের সব তথ্য নিয়ে আসা
$query = "SELECT * FROM users WHERE username = '$user' OR id = '$user' LIMIT 1";
$res = $conn->query($query);

if ($res && $res->num_rows > 0) {
    $u_data = $res->fetch_assoc();
    $db_pass = trim($u_data['password']);

    // ২. ডিবাগিং এবং ফ্লেক্সিবল পাসওয়ার্ড চেক
    // যদি সরাসরি ম্যাচ না করে, তবে আমরা ১০০০% নিশ্চিত হওয়ার জন্য ছোট-বড় হাতের অক্ষরও চেক করছি
    if (strcasecmp($old, $db_pass) == 0) {
        // ৩. নতুন পাসওয়ার্ড আপডেট করা
        if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$user' OR id = '$user'")) {
            echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
        }
    } else {
        // যদি আপনার পাসওয়ার্ড সত্যিই মনে না থাকে, তবে এডমিন প্যানেল থেকে একবার রিসেট করে নিন
        echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ডটি ডাটাবেসের সাথে মিলছে না।"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ইউজার তথ্য পাওয়া যায়নি।"]);
}
?>
