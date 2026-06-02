<?php
// =========================================================================
//  🎰 BETLOVER77 / OWNER অফিসিয়াল গেম ইন্টিগ্রেশন এপিআই কলব্যাক সার্ভিস 
// =========================================================================

ob_start();
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

include "db.php"; 

$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if (!$data || empty($data)) {
    echo json_encode(["status" => "error", "message" => "❌ Invalid JSON Payload Input Reference!"]);
    exit();
}

$action   = isset($data['action']) ? trim($data['action']) : '';
$username = isset($data['username']) ? mysqli_real_escape_string($conn, trim($data['username'])) : '';
$amount   = isset($data['amount']) ? floatval($data['amount']) : 0;
$wallet   = isset($data['wallet']) ? mysqli_real_escape_string($conn, trim($data['wallet'])) : 'main';

// 🎯 গেমের নাম সঠিকভাবে ডিটেক্ট করা (গেম হিস্টোরির জন্য এটি অত্যন্ত জরুরি)
$dynamic_game_name = "Casino Game"; 
if (!empty($data['game'])) {
    $dynamic_game_name = mysqli_real_escape_string($conn, trim($data['game']));
} elseif (isset($_GET['game']) && !empty($_GET['game'])) {
    $dynamic_game_name = mysqli_real_escape_string($conn, trim($_GET['game']));
}

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "❌ Empty Player Username Credentials!"]);
    exit();
}

// ডাটাবেজ থেকে প্লেয়ার চেক
$user_query = $conn->query("SELECT * FROM users WHERE username = '$username' OR id = '$username'");
$u_data = $user_query->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "❌ Player Account Profile Not Found!"]);
    exit();
}

$player_username = $u_data['username']; // রিয়েল ইউজারনেম

// ওয়ালেট সিলেকশন
$bal_col = 'balance';
$turn_col = 'main_t';

$wallet = strtolower($wallet);
if ($wallet === 'pb_balance' || $wallet === 'pb') {
    $bal_col = 'pb_balance';
    $turn_col = 'pb_t';
} elseif ($wallet === 'bonus') {
    $bal_col = 'bonus_balance';
    $turn_col = 'bonus_t';
}

$user_current_balance = floatval($u_data[$bal_col]);

// 💸 বাজি ধরা (Bet Action) - ব্যালেন্স কাটা
if ($action === "bet") {
    if ($user_current_balance < $amount || $user_current_balance <= 0) {
        echo json_encode(["status" => "error", "message" => "❌ Insufficient Balance!"]);
        exit();
    }
    
    // ব্যালেন্স মাইনাস এবং টার্নওভার প্লাস করা হচ্ছে
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '$player_username'");
    
    if ($update) {
        // হিস্টোরিতে গেমের নাম ও স্ট্যাটাস 'pending' সেভ করা হচ্ছে
        $conn->query("INSERT INTO bets (username, amount, game_id, status, created_at) VALUES ('$player_username', '$amount', '$dynamic_game_name', 'pending', NOW())");
        
        echo json_encode([
            "status" => "ok",
            "message" => "Bet Accepted Successfully.",
            "balance" => floatval($user_current_balance - $amount)
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "❌ Database Wallet Debit Sync Failed!"]);
    }
    exit();
}

// 🏆 জয়ী হওয়া (Win Action) - ব্যালেন্স যোগ করা
elseif ($action === "win") {
    // ব্যালেন্স প্লাস করা হচ্ছে
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$player_username'");
    
    if ($update) {
        // সর্বশেষ পেন্ডিং বেটটিকে 'win' এবং win_amount আপডেট করা হচ্ছে
        $conn->query("UPDATE bets SET status = 'win', win_amount = '$amount' WHERE username = '$player_username' AND status = 'pending' ORDER BY id DESC LIMIT 1");
        
        // আপডেটেড ব্যালেন্স রিড করা
        $fresh_query = $conn->query("SELECT $bal_col FROM users WHERE username = '$player_username'");
        $fresh_data = $fresh_query->fetch_assoc();
        
        echo json_encode([
            "status" => "ok",
            "message" => "Win Settled Successfully.",
            "balance" => floatval($fresh_data[$bal_col])
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "❌ Database Wallet Credit Sync Failed!"]);
    }
    exit();
}

// ↩️ কোনো অ্যাকশন ম্যাচ না করলে ফলব্যাক
else {
    echo json_encode(["status" => "error", "message" => "❌ Bypassed Route!"]);
    exit();
}

ob_end_flush();
?>
