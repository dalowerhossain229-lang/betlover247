<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. ইনপুট নেওয়া ও পরিষ্কার করা
$user = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
$pass = trim($_POST['password'] ?? '');
$ref_by = mysqli_real_escape_string($conn, trim($_POST['ref_by'] ?? ''));

// ২. ইনপুট চেক
if (empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "ইউজারনাম এবং পাসওয়ার্ড দিতে হবে!"]);
    exit;
}

// ৩. ইউজার আগে থেকে আছে কি না চেক করা
$check = $conn->query("SELECT id FROM users WHERE username = '$user'");
if ($check && $check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই ইউজারনাম অলরেডি ব্যবহার করা হয়েছে!"]);
    exit;
}

// ৪. পাসওয়ার্ড হাশ করা (নিরাপত্তার জন্য)
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// ৫. নতুন ইউজার ইনসার্ট করা (ref_by কলামে রেফার কোড সেভ হচ্ছে)
$sql = "INSERT INTO users (username, password, ref_by, balance, role, status) 
        VALUES ('$user', '$hashed_pass', '$ref_by', 0.00, 'user', 'active')";

if ($conn->query($sql)) {
    $_SESSION['username'] = $user; // সেশন সেট করা
    echo json_encode(["status" => "success", "message" => "অভিনন্দন! একাউন্ট সফলভাবে খোলা হয়েছে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
