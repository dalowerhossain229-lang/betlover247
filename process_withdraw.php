<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$uid = $_SESSION['user_id'];
$amount = (float)$_POST['amount'];
$num = $_POST['number'];
$meth = $_POST['method'];

// ব্যালেন্স চেক
$user = $conn->query("SELECT balance FROM users WHERE username='$uid'")->fetch_assoc();

if($user['balance'] < $amount) {
    echo json_encode(["status" => "error", "message" => "পর্যাপ্ত ব্যালেন্স নেই!"]);
} else {
    // ব্যালেন্স কেটে রাখা এবং রিকোয়েস্ট সেভ করা
    $conn->query("UPDATE users SET balance = balance - $amount WHERE username='$uid'");
    $sql = "INSERT INTO withdraws (user_id, amount, method, number, status) VALUES ('$uid', '$amount', '$meth', '$num', 'pending')";
    $conn->query($sql);
    echo json_encode(["status" => "success", "message" => "উইথড্র রিকোয়েস্ট সফল হয়েছে!"]);
}
?>
