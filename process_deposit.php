<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আগে লগইন করুন!"]);
    exit;
}

$user = $_SESSION['user_id'];
$amount = mysqli_real_escape_string($conn, $_POST['amount'] ?? '');
$trx_id = mysqli_real_escape_string($conn, $_POST['trx_id'] ?? '');

if (empty($amount) || empty($trx_id)) {
    echo json_encode(["status" => "error", "message" => "টাকার পরিমাণ এবং TrxID দিন!"]);
    exit;
}

// ডাটাবেসে ডিপোজিট রিকোয়েস্ট সেভ করা
$sql = "INSERT INTO deposits (username, amount, trx_id, status) VALUES ('$user', '$amount', '$trx_id', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "ডিপোজিট রিকোয়েস্ট সফল! এডমিন চেক করে ব্যালেন্স যোগ করে দিবে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "এই TrxID টি ইতিমধ্যে ব্যবহার করা হয়েছে!"]);
}
?>
