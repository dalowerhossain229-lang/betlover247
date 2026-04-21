<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. ইউজার লগইন আছে কি না চেক করা
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আগে লগইন করুন!"]);
    exit;
}

$user = $_SESSION['user_id'];
$amount = floatval($_POST['amount'] ?? 0);
$number = mysqli_real_escape_string($conn, $_POST['number'] ?? '');
$method = mysqli_real_escape_string($conn, $_POST['method'] ?? '');

// ২. ইনপুট ভ্যালিডেশন
if ($amount <= 0 || empty($number) || empty($method)) {
    echo json_encode(["status" => "error", "message" => "সবগুলো তথ্য সঠিক ভাবে দিন!"]);
    exit;
}

// ৩. ইউজারের বর্তমান ব্যালেন্স চেক করা
$res = $conn->query("SELECT balance FROM users WHERE username = '$user'");
$userData = $res->fetch_assoc();
$currentBalance = $userData['balance'];

if ($amount > $currentBalance) {
    echo json_encode(["status" => "error", "message" => "আপনার একাউন্টে পর্যাপ্ত ব্যালেন্স নেই!"]);
    exit;
}

// ৪. ব্যালেন্স থেকে টাকা কেটে নেওয়া (তাত্ক্ষণিকভাবে)
$newBalance = $currentBalance - $amount;
$conn->query("UPDATE users SET balance = $newBalance WHERE username = '$user'");

// ৫. উইথড্র রিকোয়েস্ট ডাটাবেসে জমা করা
$sql = "INSERT INTO withdraws (username, amount, number, method, status) VALUES ('$user', '$amount', '$number', '$method', 'pending')";

if ($conn->query($sql)) {
    // সেশন ব্যালেন্স আপডেট করা
    $_SESSION['balance'] = $newBalance;
    echo json_encode(["status" => "success", "message" => "উইথড্র রিকোয়েস্ট সফল! এডমিন চেক করে পেমেন্ট করে দিবে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "সার্ভার এরর! ডাটাবেস টেবিল চেক করুন।"]);
}
?>
