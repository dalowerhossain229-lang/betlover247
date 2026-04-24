<?php
include 'db.php';

// গেম প্রোভাইডার থেকে আসা ডাটা রিসিভ করা (JSON format)
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action']; // যেমন: getBalance, bet, win
$user_id = $data['user_id'];

if($action == "getBalance") {
    $res = $conn->query("SELECT balance FROM users WHERE username = '$user_id'");
    $row = $res->fetch_assoc();
    echo json_encode(["status" => "success", "balance" => $row['balance']]);
}

if($action == "bet") {
    $amount = $data['amount'];
    // ব্যালেন্স থেকে টাকা কাটা
    $conn->query("UPDATE users SET balance = balance - $amount WHERE username = '$user_id'");
    echo json_encode(["status" => "success"]);
}

if($action == "win") {
    $amount = $data['amount'];
    // ব্যালেন্সের সাথে জেতা টাকা যোগ করা
    $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$user_id'");
    echo json_encode(["status" => "success"]);
}
?>
