<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. চেক করা ইউজার লগইন আছে কি না
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আগে লগইন করুন!"]);
    exit;
}

$phone  = $_POST['phone'] ?? '';
$amount = $_POST['amount'] ?? '';
$userId = $_SESSION['user_id'];

// ২. খালি ঘর চেক করা
if (empty($phone) || empty($amount)) {
    echo json_encode(["status" => "error", "message" => "নম্বর এবং টাকার পরিমাণ দিন!"]);
    exit;
}

// ৩. ইউজারের বর্তমান ব্যালেন্স চেক করা
$res = $conn->query("SELECT balance FROM users WHERE username = '$userId'");
$user_data = $res->fetch_assoc();

if ($user_data['balance'] < $amount) {
    echo json_encode(["status" => "error", "message" => "আপনার ব্যালেন্স পর্যাপ্ত নয়!"]);
    exit;
}

// ৪. উইথড্র রিকোয়েস্ট ডাটাবেসে সেভ করা
$sql = "INSERT INTO transactions (user_id, type, amount, target_number, status) 
        VALUES ('$userId', 'withdraw', '$amount', '$phone', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "উইথড্র রিকোয়েস্ট সফলভাবে পাঠানো হয়েছে!"]);
} else {
    echo json_encode(["status" => "error", "message" => "সার্ভার এরর! ডাটাবেস টেবিল চেক করুন।"]);
}
?>
