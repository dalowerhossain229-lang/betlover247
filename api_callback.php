<?php
// ===================================================================================
// 🎰 BETLOVER777 / DKWIN ওরিজিনাল গ্লোবাল ইউনিভার্সাল এপিআই কলব্যাক মাস্টার বর্ম
// ===================================================================================
ob_start();
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include 'db.php';

// 🔌 ১. রিয়েল-টাইম মাল্টিপ্লেয়ার গেম বাজি ও উইন প্রসেসর গেটওয়ে (POST)
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "🚨 Invalid JSON API Payload Input Reference!"]);
    exit();
}

$action = isset($data['action']) ? $data['action'] : '';
$username = isset($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
$amount = isset($data['amount']) ? floatval($data['amount']) : 0;
$wallet = isset($data['wallet']) ? mysqli_real_escape_string($conn, $data['wallet']) : 'main';

// 🔌 [API_CALLBACK ওরিজিনাল গেম নেম ফিল্টার বর্ম ভাই ভাই]
$dynamic_game_name = 'Casino Game'; // Default Fallback
if (!empty($data['game'])) {
    $dynamic_game_name = mysqli_real_escape_string($conn, $data['game']);
} else {
    $get_game_type = isset($_GET['game']) ? mysqli_real_escape_string($conn, $_GET['game']) : '';
    if (!empty($get_game_type)) {
        $dynamic_game_name = $get_game_type;
    } else {
        $dynamic_game_name = 'Present-Game'; // অটো-বট লুপের জন্য লাকি ব্যাকআপ লক ওস্তাদ!
    }
}


if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "❌ Empty Player Username Credentials!"]);
    exit();
}

// ইউজার ব্যালেন্স ও ডাটা যাচাই লুপ ভাই ভাই
$user_query = $conn->query("SELECT * FROM users WHERE username = '$username' OR id = '$username'");
$u_data = $user_query->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "Player Account Profile Not Found inside Main Database Node!"]);
    exit();
}

// 🎛️ ৪. প্লেয়ারের ওয়ালেট অ্যাকাউন্ট লাইভ মেকানিজম (pb, bonus, main এলাইনমেন্ট লক ভাই)
$bal_col = 'balance';
$turn_col = 'main_t';

$wallet = strtolower($wallet);
if ($wallet === 'pb') {
    $bal_col = 'pb_balance';
    $turn_col = 'pb_t';
} elseif ($wallet === 'bonus') {
    $bal_col = 'bonus_balance';
    $turn_col = 'bonus_t';
}

$user_current_balance = floatval($u_data[$bal_col]);

// 🛫 ২. বাজি ধরার রিয়েল-টাইম একশন প্রসেসর (`action == "bet"`)
if ($action == "bet") {
    if ($user_current_balance < $amount || $user_current_balance <= 0) {
        echo json_encode(["status" => "error", "message" => "❌ Insufficient Balance in Selected Wallet!"]);
        exit();
    }

    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '{$u_data['username']}'");
    
    if ($update) {
        // 🔒 ডাটাবেজের bets টেবিলে গেমের আসল নাম সহ ওয়ান-শটে বাজি লগ এন্ট্রি মারা ভাই ভাই
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('{$u_data['username']}', '$amount', '$dynamic_game_name', 'bet')");
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted Successfully", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Wallet Debit Sync Failed!"]);
    }
} 
// 💰 ৩. বাজি জেতা বা হারার রিয়েল-টাইম সেটেলমেন্ট একশন প্রসেসর (`action == "win"`)
else if ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount, $turn_col = $turn_col + $amount WHERE username = '{$u_data['username']}'");
    
    if ($update) {
        // 🔒 [মাস্টারস্ট্রোক ফিক্সড বর্ম]: বাজি ধরার মূল amount কলাম এক চুলও নড়চড় না করে—শুধুমাত্র status কলামটি 'win' এ আপডেট করার চাবি ভাই ভাই
        $conn->query("UPDATE bets SET status = 'win' WHERE username = '{$u_data['username']}' ORDER BY id DESC LIMIT 1");
        
        $fresh_user_query = $conn->query("SELECT * FROM users WHERE username = '{$u_data['username']}'");
        $fresh_user = $fresh_user_query->fetch_assoc();
        echo json_encode(["status" => "ok", "message" => "Win Settled Successfully", "balance" => floatval($fresh_user[$bal_col])]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Wallet Credit Sync Failed!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "🚨 Bypassed B2B Callback Action Endpoint Command Route!"]);
}
ob_end_flush();
?>
