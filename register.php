<?php
include 'db.php';
header('Content-Type: application/json');

// ইনপুট ডাটা নেওয়া (সরাসরি $_POST থেকে না নিয়ে ফিল্টার করা নিরাপদ)
$name = mysqli_real_escape_string($conn, $_POST['fullName'] ?? '');
$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
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
    // নতুন ইউজার ইনসার্ট করা (ব্যালেন্স ৫০০ টাকা বোনাসসহ)
    // নিশ্চিত করুন আপনার ডাটাবেসে full_name কলামটি আছে
    $sql = "INSERT INTO users (full_name, username, password, balance) VALUES ('$name', '$user', '$hashed_pass', 500.00)";
    
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "রেজিস্ট্রেশন সফল হয়েছে! লগইন করুন।"]);
    } else {
        // ডাটাবেস এরর মেসেজ দেখার জন্য (ডেভেলপমেন্টের জন্য)
        echo json_encode(["status" => "error", "message" => "সার্ভার এরর! " . $conn->error]);
    }
}
?>
