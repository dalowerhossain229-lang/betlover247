<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. জাভাস্ক্রিপ্ট থেকে পাঠানো ডাটা রিসিভ করা
$user = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
$pass = mysqli_real_escape_string($conn, trim($_POST['password'] ?? ''));
$ref_by = mysqli_real_escape_string($conn, trim($_POST['ref_by'] ?? ''));

// ২. ইনপুট চেক
if (empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "ইউজারনেম এবং পাসওয়ার্ড উভয়ই দিন!"]);
    exit;
}

// ৩. একই নামে ইউজার আছে কি না চেক
$check = $conn->query("SELECT id FROM users WHERE username = '$user'");
if ($check && $check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই ইউজারনেমটি ইতিমধ্যে কেউ ব্যবহার করেছে!"]);
    exit;
}

// ৪. নতুন ইউজার সেভ করা (রেফার কোডসহ)
$sql = "INSERT INTO users (username, password, ref_by, balance, role) 
        VALUES ('$user', '$pass', '$ref_by', 0, 'user')";

if ($conn->query($sql)) {
    // রেজিস্ট্রেশন সফল হলে সেশন সেট করা
    $_SESSION['user_id'] = $user;
    echo json_encode(["status" => "success", "message" => "অভিনন্দন! আপনার একাউন্ট সফলভাবে খোলা হয়েছে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
