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
    // ১. ইউজারের ৩টি ব্যালেন্স নেওয়া
    $u_sql = $conn->query("SELECT balance, pb_balance, bonus_balance FROM users WHERE username = '$username'");
    $u = $u_sql->fetch_assoc();

    $main_b = (float)($u['balance'] ?? 0);
    $pb_b = (float)($u['pb_balance'] ?? 0);
    $bonus_b = (float)($u['bonus_balance'] ?? 0);
    $total_b = $main_b + $pb_b + $bonus_b;

    if ($total_b >= $amount) {
        // ২. পারসেন্টেজ অনুযায়ী টাকা ভাগ করা
        $main_cut = ($total_b > 0) ? ($main_b / $total_b) * $amount : 0;
        $pb_cut = ($total_b > 0) ? ($pb_b / $total_b) * $amount : 0;
        $bonus_cut = ($total_b > 0) ? ($bonus_b / $total_b) * $amount : 0;

        // ৩. ব্যালেন্স কাটা এবং ৩টি টার্নওভার একসাথে আপডেট করা
        $conn->query("UPDATE users SET 
            balance = balance - $main_cut, 
            pb_balance = pb_balance - $pb_cut, 
            bonus_balance = bonus_balance - $bonus_cut,
            turnover = turnover + $amount,
            pb_turnover = pb_turnover + $amount,
            bonus_turnover = bonus_turnover + $amount
            WHERE username = '$username'");
        
        if ($conn->affected_rows > 0) {
            $conn->query("INSERT INTO game_logs (username, game_name, action, amount, tx_id) VALUES ('$username', '$game', 'bet', $amount, '$tx_id')");
            echo json_encode(["status" => "ok", "message" => "Smart Bet Accepted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Transaction Failed"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Insufficient Total Balance"]);
    }
}

    // ৩. জেতার টাকা যোগ করা (Win) - আনুপাতিক ডিস্ট্রিবিউশন
    elseif ($action == "win") {
        $u_sql = $conn->query("SELECT balance, pb_balance, bonus_balance FROM users WHERE username = '$username'");
        $u = $u_sql->fetch_assoc();

        $main_b = (float)($u['balance'] ?? 0);
        $pb_b = (float)($u['pb_balance'] ?? 0);
        $bonus_b = (float)($u['bonus_balance'] ?? 0);
        $total_b = $main_b + $pb_b + $bonus_b;

        $safe_total = ($total_b > 0) ? $total_b : 1;

        // অনুপাত অনুযায়ী ভাগ করা
        $main_win = ($main_b / $safe_total) * $amount;
        $pb_win = ($pb_b / $safe_total) * $amount;
        $bonus_win = ($bonus_b / $safe_total) * $amount;

        $conn->query("UPDATE users SET 
            balance = balance + $main_win, 
            pb_balance = pb_balance + $pb_win, 
            bonus_balance = bonus_balance + $bonus_win 
            WHERE username = '$username'");

        if ($conn->affected_rows > 0) {
            $conn->query("INSERT INTO game_logs (username, game_name, action, amount, tx_id) VALUES ('$username', '$game', 'win', $amount, '$tx_id')");
            echo json_encode(["status" => "ok", "message" => "Win Distributed"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Update Failed"]);
        }
    }

