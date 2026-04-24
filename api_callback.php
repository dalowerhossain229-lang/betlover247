<?php
include 'db.php';
header('Content-Type: application/json');

// প্রোভাইডার থেকে আসা JSON ডাটা রিসিভ করা
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
    exit;
}

$action = $data['action']; // getBalance, bet, win, refund
$username = mysqli_real_escape_string($conn, $data['username']);
$amount = floatval($data['amount'] ?? 0);
$tx_id = mysqli_real_escape_string($conn, $data['tx_id'] ?? '');
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Casino');

// ১. ব্যালেন্স চেক করা
if ($action == "getBalance") {
    $res = $conn->query("SELECT balance FROM users WHERE username = '$username'");
    $row = $res->fetch_assoc();
    echo json_encode(["status" => "ok", "balance" => $row['balance'] ?? 0]);
}

// ২. বাজি ধরা (Bet) - ব্যালেন্স কাটা
elseif ($action == "bet") {
    $conn->query("UPDATE users SET balance = balance - $amount WHERE username = '$username' AND balance >= $amount");
    if ($conn->affected_rows > 0) {
        $conn->query("INSERT INTO game_logs (username, game_name, action, amount, tx_id) VALUES ('$username', '$game', 'bet', $amount, '$tx_id')");
        echo json_encode(["status" => "ok", "message" => "Bet Accepted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance"]);
    }
}

// ৩. জয়ী হওয়া (Win) - ব্যালেন্স যোগ করা
elseif ($action == "win") {
    $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$username'");
    $conn->query("INSERT INTO game_logs (username, game_name, action, amount, tx_id) VALUES ('$username', '$game', 'win', $amount, '$tx_id')");
    echo json_encode(["status" => "ok", "message" => "Win Processed"]);
}
?>
