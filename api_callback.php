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
$db_wallet_column = 'balance'; // ডিফল্ট মেইন ওয়ালেট কলাম ম্যাপ

if ($wallet === 'pb') {
    $db_wallet_column = 'pb_balance';
} elseif ($wallet === 'bonus') {
    $db_wallet_column = 'bonus_balance';
}

$current_balance = floatval($user[$db_wallet_column]);

// ===================================================================================
// 🛫 ১. বাজি ধরার অ্যাকশন রাউট (ACTION: BET - ১০০% একুরেট টাকা কাটার লোহার খাঁচা লক ওস্তাদ!)
// ===================================================================================
if ($action === 'bet' || $action === 'deal') {
    $final_bet_deduct = $amount; 

    if ($current_balance < $final_bet_deduct || $current_balance <= 0) {
        echo json_encode(["status" => "error", "balance" => $current_balance, "message" => "Insufficient account balance!"]);
        exit();
    }

    // 🔒 [গ্র্যান্ড কিংস কারেকশন ট্রিক]: স্ক্রিনশটের ৭৮ লাইনের ওল্ড ডুয়াল রাইটিং জ্যাম উপড়ে ফেলে সিঙ্গেল কুয়েরিতে সেফ টাকা কাটা লক!
    $new_balance = $current_balance - $final_bet_deduct;
    $update_stmt = $conn->prepare("UPDATE users SET $db_wallet_column = ? WHERE id = ?");
    $update_stmt->bind_param("di", $new_balance, $userId);
    
    if ($update_stmt->execute()) {
        // ব্যাকগ্রাউন্ডে কোনো লকিং জ্যাম ছাড়া আলাদা ইন্ডিপেন্ডেন্ট কুয়েরিতে ওরিজিনাল টার্নওভার প্লাস লক ভাই ভাই!
        $turn_col = ($wallet === 'pb') ? 'pb_t' : (($wallet === 'bonus') ? 'bonus_t' : 'main_t');
        $conn->query("UPDATE users SET $turn_col = $turn_col + $final_bet_deduct WHERE id = $userId");

        // ওরিজিনাল হিস্ট্রি লগে বাজি ইনস্ট্যান্ট এন্ট্রি পুশ
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('".$user['username']."', $final_bet_deduct, '$game', 'pending')");

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
        // ওরিজিনাল bet_logs হিস্ট্রি টেবিল ও লেজারে প্রফিট ডেটা রাইট লক ওস্তাদ
        $final_history_bet = ($bet_amount > 0) ? $bet_amount : $amount;
        $log_stmt = $conn->prepare("INSERT INTO bet_logs (user_id, username, game, bet_amount, win_amount, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $log_stmt->bind_param("issdds", $userId, $user['username'], $game, $final_history_bet, $amount, $status);
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
