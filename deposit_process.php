<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আগে লগইন করুন!"]);
    exit;
}

$user = $_SESSION['user_id'];
$amount = mysqli_real_escape_string($conn, $_POST['amount'] ?? '');
$trx_id = mysqli_real_escape_string($conn, $_POST['trx_id'] ?? '');
$method = mysqli_real_escape_string($conn, $_POST['method'] ?? 'Unknown'); // নতুন মেথড ফিল্ড

// ২. খালি ঘর চেক
if (empty($amount) || empty($trx_id)) {
    echo json_encode(["status" => "error", "message" => "টাকার পরিমাণ এবং TrxID দিন!"]);
    exit;
}

// ৩. TrxID ডুপ্লিকেট চেক (যাতে কেউ একই আইডি দুইবার না দেয়)
$check = $conn->query("SELECT id FROM deposits WHERE trx_id = '$trx_id'");
if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই TrxID টি ইতিমধ্যে ব্যবহার করা হয়েছে!"]);
    exit;
}

// ৪. ডাটাবেসে সেভ করা (মেথডসহ)
$sql = "INSERT INTO deposits (username, amount, trx_id, method, status) VALUES ('$user', '$amount', '$trx_id', '$method', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "ডিপোজিট রিকোয়েস্ট সফল! এডমিন চেক করে $method মেথডে টাকা যোগ করে দিবে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "সার্ভার এরর: " . $conn->error]);
}
?>
