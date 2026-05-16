<?php
include 'db.php';
header('Content-Type: application/json');

// ১. এভিয়েটোর থেকে আসা ডেটা ধরা
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
    exit;
}

$action = $data['action'];
$username = mysqli_real_escape_string($conn, $data['username']);
$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Casino');

// ২. ইউজারের বর্তমান ওয়ালেট সিলেক্ট করে ব্যালেন্স চেক করা
$u_sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found"]);
    exit;
}

// আপনার সিস্টেম অনুযায়ী ওয়ালেট নির্ধারণ
$wallet = $u_data['active_wallet'] ?? 'main';

if ($wallet == 'pb') {
    $bal_col = "pb_balance"; $turn_col = "pb_t";
} elseif ($wallet == 'bonus') {
    $bal_col = "bonus_balance"; $turn_col = "bonus_t";
} else {
    $bal_col = "balance"; $turn_col = "main_t";
}
if ($action == "bet") {
    // ডাইনামিক ভেরিয়েবল বাদ দিয়ে সরাসরি আপনার ডাটাবেজের আসল কলাম 'balance' লিখে দেওয়া হলো
    $update = $conn->query("UPDATE users SET balance = balance - $amount, main_t = main_t + $amount WHERE username = '$username'");
    
    if ($update) {
        // ডাটাবেজ থেকে প্লেয়ারের নতুন ব্যালেন্স রিড করা
        $current_bal = floatval($u_data['balance']) - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}


// 💰 ক্যাশআউট বা জেতার লজিক
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    
    if ($update) {
        $current_bal = floatval($u_data[$bal_col]) + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔄 রিফান্ড লজিক
elseif ($action == "refund") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    if ($update) {
        $current_bal = floatval($u_data[$bal_col]) + $amount;
        echo json_encode(["status" => "ok", "message" => "Refund Processed", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
?>
