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
// 🎰 ১. বাজি ধরার লজিক (আনুমানিক ৪১-৫০ নম্বর লাইনের কাছাকাছি এটি রিপ্লেস করুন)
if ($action == "bet") {
    $update = $conn->query("UPDATE users SET balance = balance - $amount, main_t = main_t + $amount WHERE username = '$username'");
    
    if ($update) {
        // 📝 হিস্ট্রি পেজে দেখানোর জন্য bets টেবিলে বাজি ধরার রেকর্ড সেভ করা হলো
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$username', '$amount', 'Aviator', 'bet')");
        
        $current_bal = floatval($u_data['balance']) - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 💰 ২. ক্যাশআউট বা জেতার লজিক
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$username'");
    
    if ($update) {
        // 📝 game_id এর জায়গায় ডাইনামিক ভেরিয়েবল '$game' ব্যবহার করা হলো যাতে Casino নামের সাথে ম্যাচ করে
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '$username' AND game_id = '$game' AND status = 'bet' ORDER BY id DESC LIMIT 1");
        
        $current_bal = floatval($u_data['balance']) + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔄 রিফান্ড লজিক
elseif ($action == "refund") {
    $update = $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$username'");
    if ($update) {
        $current_bal = floatval($u_data['balance']) + $amount;
        echo json_encode(["status" => "ok", "message" => "Refund Processed", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔴 ৩. লস লজিক (প্লেন ক্রাশ খেলে এটি অটোমেটিক কাজ করবে)
elseif ($action == "loss") {
    // 📝 এখানেও game_id এর জায়গায় ডাইনামিক ভেরিয়েবল '$game' ব্যবহার করা হলো
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '$username' AND game_id = '$game' AND status = 'bet' ORDER BY id DESC LIMIT 1");
    echo json_encode(["status" => "ok", "message" => "Loss Recorded"]);
}
?>


