<?php
// ===================================================================================
// 🎰 CASINO GAME API CALLBACK GATEWAY PROVIDER - MASTER PROTOCAL BARM (১০০% একুরেট ব্যালেন্স বর্ম)
// ===================================================================================
ob_start();
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include 'db.php';

// নোড ক্যাসিনো ইঞ্জিন থেকে পাঠানো কাঁচা জেসন পেলোড ডাটা রিড করা
$raw_input = file_get_contents('php://input');
$data = json_decode($raw_input, true) ?? $_POST;

$action = $data['action'] ?? '';
$username = $data['username'] ?? $data['userId'] ?? '';
$amount = floatval($data['amount'] ?? 0);
$wallet = $data['wallet'] ?? 'main';
$game = $data['game'] ?? '';
$status = $data['status'] ?? '';
$bet_amount = floatval($data['bet_amount'] ?? $amount);

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Missing username configuration token!"]);
    exit();
}

// 🔒 [🔒 গ্র্যান্ড কিংস ডাটাবেজ ট্র্যাকার লক]: প্লেয়ার আইডি বা ইউজারনেম একুরেট ক্যাচ করার বর্ম
$user_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR id = ?");
$user_stmt->bind_param("ss", $username, $username);
$user_stmt->execute();
$user_res = $user_stmt->get_result();
$user = $user_res->fetch_assoc();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "Player profile session not found in database!"]);
    exit();
}

$userId = $user['id'];

// 🔒 [🔒 মেইন ওয়ালেট কলাম ডাইনামিক ফিল্টারিং বর্ম]: 
// আপনার ডাটাবেজে মেইন টাকার কলাম balance, money বা wallet যা-ই থাকুক, ওয়ান-শটে নিখুঁত ম্যাচ ট্রিক!
$db_wallet_column = 'balance'; 
if (!isset($user['balance']) && isset($user['money'])) {
    $db_wallet_column = 'money';
} elseif (!isset($user['balance']) && isset($user['wallet'])) {
    $db_wallet_column = 'wallet';
}

if ($wallet === 'pb') {
    $db_wallet_column = isset($user['pb_balance']) ? 'pb_balance' : (isset($user['pb_money']) ? 'pb_money' : 'pb_wallet');
} elseif ($wallet === 'bonus') {
    $db_wallet_column = isset($user['bonus_balance']) ? 'bonus_balance' : (isset($user['bonus_money']) ? 'bonus_money' : 'bonus_wallet');
}

$current_balance = floatval($user[$db_wallet_column]);

// ===================================================================================
// 🛫 ১. বাজি ধরার অ্যাকশন রাউট (ACTION: BET - ১০০% একুরেট টাকা কাটার লোহার খাঁচা লক ওস্তাদ!)
// ===================================================================================
if ($action === 'bet' || $action === 'deal' || $action === 'spin') {
    $final_bet_deduct = $amount; 

    if ($current_balance < $final_bet_deduct || $current_balance <= 0) {
        echo json_encode(["status" => "error", "balance" => $current_balance, "message" => "Insufficient account balance!"]);
        exit();
    }

    // প্লেয়ারের ওয়ালেট থেকে কাটায় কাটায় ১০০% তাজা টাকা ডেবিট বা মাইনাস লক ওস্তাদ!
    $new_balance = $current_balance - $final_bet_deduct;
    $update_stmt = $conn->prepare("UPDATE users SET $db_wallet_column = ? WHERE id = ?");
    $update_stmt->bind_param("di", $new_balance, $userId);
    
    if ($update_stmt->execute()) {
        // ব্যাকগ্রাউন্ডে কোনো লকিং জ্যাম ছাড়াই আলাদা ইন্ডিপেন্ডেন্ট কুয়েরিতে ওরিজিনাল টার্নওভার প্লাস লক ভাই ভাই!
        $turn_col = ($wallet === 'pb') ? 'pb_t' : (($wallet === 'bonus') ? 'bonus_t' : 'main_t');
        if (isset($user[$turn_col])) {
            $conn->query("UPDATE users SET $turn_col = $turn_col + $final_bet_deduct WHERE id = $userId");
        }

        echo json_encode(["status" => "ok", "balance" => $new_balance, "message" => "Bet Accepted Successfully"]);
    } else {
        echo json_encode(["status" => "error", "balance" => $current_balance, "message" => "Database write blocker!"]);
    }
    exit();
}

// ===================================================================================
// 🏆 ২. রিওয়ার্ড ও সেটেলমেন্ট অ্যাকশন রাউট (ACTION: WIN / SETTLEMENT)
// ===================================================================================
if ($action === 'win') {
    $new_balance = $current_balance + $amount;
    $update_stmt = $conn->prepare("UPDATE users SET $db_wallet_column = ? WHERE id = ?");
    $update_stmt->bind_param("di", $new_balance, $userId);
    
    if ($update_stmt->execute()) {
        // ওরিজিনাল সচল bet_logs টেবিলে সাকসেস উইন-লস এন্ট্রি লক ওস্তাদ!
        $final_history_bet = ($bet_amount > 0) ? $bet_amount : $amount;
        $final_history_status = ($amount > 0) ? "win" : "lose";
        
        $log_stmt = $conn->prepare("INSERT INTO bet_logs (user_id, username, game, bet_amount, win_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $log_stmt->bind_param("issdds", $userId, $user['username'], $game, $final_history_bet, $amount, $final_history_status);
        $log_stmt->execute();

        echo json_encode(["status" => "ok", "balance" => $new_balance, "message" => "Win Settled Successfully"]);
    } else {
        echo json_encode(["status" => "error", "balance" => $current_balance, "message" => "Database write blocker!"]);
    }
    exit();
}

// ===================================================================================
// 💰 ৩. ব্যালেন্স চেক রাউট (ACTION: BALANCE)
// ===================================================================================
if ($action === 'balance') {
    echo json_encode(["status" => "ok", "balance" => $current_balance]);
    exit();
}

echo json_encode(["status" => "error", "message" => "Unknown callback engine command action request!"]);
?>
