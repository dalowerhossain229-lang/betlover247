<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. সেশন থেকে ইউজার আইডি বা ইউজারনেম নেওয়া
$user = $_SESSION['user_id'] ?? '';

if (!$user) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আবার লগইন করুন।"]);
    exit;
}

$old = trim($_POST['old_pass']);
$new = trim($_POST['new_pass']);

// ২. ডাটাবেসে ইউজারনেম অথবা আইডি—উভয় মাধ্যমেই চেক করা
$query = "SELECT password FROM users WHERE username = '$user' OR id = '$user' LIMIT 1";
$res = $conn->query($query);

if ($res && $res->num_rows > 0) {
    $u_data = $res->fetch_assoc();
    $db_pass = trim($u_data['password']);

    // ৩. পাসওয়ার্ড চেক (খুবই সহজ পদ্ধতিতে)
    if ($old == $db_pass) {
        // ৪. পাসওয়ার্ড আপডেট করা
        $update_query = "UPDATE users SET password = '$new' WHERE username = '$user' OR id = '$user'";
        if ($conn->query($update_query)) {
            echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ডটি ভুল!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ইউজার ডাটা পাওয়া যায়নি।"]);
}
?>
