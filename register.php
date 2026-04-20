<?php
session_start(); // অটো লগইন করার জন্য সেশন শুরু করা হলো
include 'db.php';
header('Content-Type: application/json');

$name = mysqli_real_escape_string($conn, $_POST['fullName'] ?? '');
$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

if (empty($name) || empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "সবগুলো ঘর পূরণ করুন!"]);
    exit;
}

$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// চেক করা ইউজার আইডি ইতিমধ্যে আছে কি না
$check = $conn->query("SELECT id FROM users WHERE username = '$user'");

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই আইডিটি ইতিমধ্যে ব্যবহৃত হয়েছে!"]);
} else {
    // নতুন ইউজার তৈরি (ব্যালেন্স ০.০০ টাকা)
    $sql = "INSERT INTO users (full_name, username, password, balance) VALUES ('$name', '$user', '$hashed_pass', 0.00)";
    
    if ($conn->query($sql)) {
        // --- অটো লগইন লজিক ---
        $_SESSION['user_id'] = $user;
        $_SESSION['balance'] = 0.00;
        
        echo json_encode(["status" => "success", "message" => "রেজিস্ট্রেশন সফল! আপনি অটো লগইন হয়েছেন।"]);
    } else {
        echo json_encode(["status" => "error", "message" => "সার্ভার এরর!"]);
    }
}
?>
