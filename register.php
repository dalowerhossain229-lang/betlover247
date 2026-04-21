<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. ইনপুট ডাটা নেওয়া
$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

// ২. খালি ঘর চেক করা
if (empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "ইউজার আইডি এবং পাসওয়ার্ড দিন!"]);
    exit;
}

// ৩. চেক করা ইউজার আইডি ইতিমধ্যে আছে কি না
$check = $conn->query("SELECT id FROM users WHERE username = '$user'");

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই ইউজার আইডিটি ইতিমধ্যে ব্যবহার করা হয়েছে!"]);
} else {
    // ৪. পাসওয়ার্ড হ্যাশ করা এবং ইনসার্ট করা
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
    
    // আমাদের নতুন টেবিল স্ট্রাকচার অনুযায়ী কুয়েরি
    $sql = "INSERT INTO users (username, password, balance, status) VALUES ('$user', '$hashed_pass', 0.00, 'active')";

    if ($conn->query($sql)) {
        // অটো লগইন করার জন্য সেশন সেট করা
        $_SESSION['user_id'] = $user;
        echo json_encode(["status" => "success", "message" => "রেজিস্ট্রেশন সফল হয়েছে!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
    }
}
?>
