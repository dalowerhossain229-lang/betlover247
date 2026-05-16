<?php
include 'db.php';
header('Content-Type: application/json');

// ১. এভিয়েটোর থেকে আসা রিকোয়েস্ট ডাটা পড়া
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
    exit;
}

$action = $data['action'];
$username = mysqli_real_escape_string($conn, $data['username']);
$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Aviator');

// ২. ডাটাবেজ থেকে ইউজারের ওয়ালেট এবং ব্যালেন্সের তথ্য আনা
$u_sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found"]);
    exit;
}

// 🎯 ডাইনামিক ওয়ালেট সিলেকশন (প্লেয়ার স্ক্রিনে যেটি সিলেক্ট করবে সেটি ধরবে)
$wallet = $u_data['active_wallet'] ?? 'main';

if ($wallet == 'pb') {
    $bal_col = "pb_balance"; 
    $turn_col = "pb_t";
} elseif ($wallet == 'bonus') {
    $bal_col = "bonus_balance"; 
    $turn_col = "bonus_t";
} else {
    $bal_col = "balance"; 
    $turn_col = "main_t";
}

// ইউজারের কারেন্ট ব্যালেন্স চেক
$user_current_balance = floatval($u_data[$bal_col] ?? 0);

// 🎰 বাজি ধরার লজিক
if ($action == "bet") {
    // 🛡️ অ্যান্টি-ডাবল ক্লিক প্রোটোকল: ডুপ্লিকেট বাজি আটকানো
    $check_dup = $conn->query("SELECT id FROM bets WHERE username = '$username' AND amount = '$amount' AND status = 'bet' AND created_at >= NOW() - INTERVAL 2 SECOND LIMIT 1");
    if ($check_dup && $check_dup->num_rows > 0) {
        echo json_encode(["status" => "ok", "message" => "Duplicate Bypass", "balance" => $user_current_balance]);
        exit;
    }

    // প্লেয়ারের ব্যালেন্স পর্যাপ্ত আছে কি না চেক
    if ($user_current_balance < $amount) {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance!"]);
        exit;
    }

    // সিলেক্ট করা ওয়ালেট থেকে টাকা কাটার মূল কুয়েরি
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '$username'");
    
    if ($update) {
        // bets টেবিলে গেমের বাজি ইনসার্ট করা
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$username', '$amount', 'Aviator', 'bet')");
        
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 💰 ক্যাশআউট বা জেতার লজিক
elseif ($action == "win") {
    // প্লেয়ার যে ওয়ালেট সিলেক্ট করে খেলছে, জিতার পর প্রফিট ঠিক সেই ওয়ালেটেই প্লাস হবে
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    
    if ($update) {
        // চলতি 'bet' স্ট্যাটাসটিকে আপডেট করে 'win' করে দেওয়া হলো
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '$username' AND status = 'bet'");
        
        $new_balance = $user_current_balance + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔴 লস লজিক (ক্রাশ খেলে হিস্ট্রি আপডেট হবে)
elseif ($action == "loss") {
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '$username' AND status = 'bet'");
    echo json_encode(["status" => "ok", "message" => "Loss Recorded"]);
}
?>
