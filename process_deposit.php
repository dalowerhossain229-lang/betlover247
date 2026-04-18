<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "আগে লগইন করুন!"]); exit;
}

$uId = $_SESSION['user_id'];
$method = $_POST['method'] ?? '';
$amount = $_POST['amount'] ?? '';
$trxId = $_POST['trxId'] ?? '';

if (empty($amount) || empty($trxId)) {
    echo json_encode(["status" => "error", "message" => "সব তথ্য দিন!"]); exit;
}

// ট্রানজ্যাকশন টেবিলে ইনসার্ট করা
$sql = "INSERT INTO transactions (user_id, type, method, amount, trx_id, status) VALUES ('$uId', 'deposit', '$method', '$amount', '$trxId', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "ডিপোজিট রিকোয়েস্ট সফল হয়েছে! অ্যাডমিন এপ্রুভের অপেক্ষা করুন।"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>

