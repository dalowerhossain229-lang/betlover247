<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit; }
$user = $_SESSION['user_id'];

$amount = intval($_POST['amount']);
$trx = mysqli_real_escape_string($conn, $_POST['trx_id']);
$method = mysqli_real_escape_string($conn, $_POST['method']);

if ($amount < 100 || $amount > 10000) {
    echo json_encode(["status" => "error", "message" => "ভুল অ্যামাউন্ট!"]);
    exit;
}

$sql = "INSERT INTO pb_deposits (username, amount, method, trx_id, status) 
        VALUES ('$user', '$amount', '$method', '$trx', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "PB রিকোয়েস্ট সফল! এডমিন চেক করে বোনাস ও টার্নওভার সেট করে দিবে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
}
?>
