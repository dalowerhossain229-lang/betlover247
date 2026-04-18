<?php
include 'db.php';
header('Content-Type: application/json');

// ইনপুট ডাটা নেওয়া
$name = $_POST['fullName'] ?? '';
$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

// ভ্যালিডেশন
if (empty($name) || empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "সবগুলো ঘর পূরণ করুন!"]);
    exit;
}

// পাসওয়ার্ড নিরাপদ রাখতে হ্যাশ করা
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// চেক করা ইউজার আইডি আগে থেকে আছে কি না
$check = $conn->query("SELECT id FROM users WHERE username = '$user'");
if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই ইউজার আইডিটি ইতিমধ্যে ব্যবহৃত হয়েছে!"]);
} else {
    // নতুন ইউজার ডাটাবেসে ইনসার্ট করা (সাথেই ৫০০ টাকা বোনাস ব্যালেন্স)
    $sql = "INSERT INTO users (full_name, username, password, balance) VALUES ('$name', '$user', '$hashed_pass', 500.00)";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "রেজিস্ট্রেশন সফল হয়েছে! লগইন করুন।"]);
    } else {
        echo json_encode(["status" => "error", "message" => "সার্ভার এরর! ডাটাবেস টেবিল চেক করুন।"]);
    }
}
?>
