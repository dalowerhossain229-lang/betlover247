<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. সেশন ভেরিফাই করা
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "সেশন আউট! আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
// trim() ফাংশন ইনপুটের সব বাড়তি স্পেস কেটে দিবে
$old = trim($_POST['old_pass']);
$new = trim($_POST['new_pass']);

// ২. ডাটাবেস থেকে ইউজারের পাসওয়ার্ড নিয়ে আসা
$query = "SELECT password FROM users WHERE username = '$user' OR id = '$user'";
$res = $conn->query($query);

if ($res && $res->num_rows > 0) {
    $u_data = $res->fetch_assoc();
    // ডাটাবেসে থাকা পাসওয়ার্ড থেকেও বাড়তি স্পেস কেটে নেওয়া
    $db_pass = trim($u_data['password']);

    // ৩. পাসওয়ার্ড মেলাবো (সাধারণ তুলনা পদ্ধতি)
    if ($old == $db_pass) {
        // ৪. নতুন পাসওয়ার্ড আপডেট করা
        if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$user' OR id = '$user'")) {
            echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
        }
    } else {
        // এখনও না মিললে এই মেসেজটি আসবে
        echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ডটি সঠিক নয়।"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ইউজার তথ্য পাওয়া যায়নি।"]);
}
?>
