<?php
include 'db.php';
header('Content-Type: application/json');

// ইনপুট নেওয়া
$name = mysqli_real_escape_string($conn, $_POST['fullName'] ?? '');
$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

// ভ্যালিডেশন
if (empty($name) || empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "সব ঘর পূরণ করুন!"]);
    exit;
}

// পাসওয়ার্ড এনক্রিপশন
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// চেক করা ইউজার আইডি আগে থেকে আছে কি না
$check = $conn->query("SELECT id FROM users WHERE username = '$user'");

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই আইডিটি ইতিমধ্যে ব্যবহৃত হয়েছে!"]);
} else {
    // নতুন ইউজার তৈরি (ব্যালেন্স ০.০০ টাকা)
    $sql = "INSERT INTO users (full_name, username, password, balance) VALUES ('$name', '$user', '$hashed_pass', 0.00)";
    
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "রেজিস্ট্রেশন সফল! লগইন করুন।"]);
    } else {
        echo json_encode(["status" => "error", "message" => "সার্ভার এরর! ডাটাবেস টেবিল চেক করুন।"]);
    }
}
?>
