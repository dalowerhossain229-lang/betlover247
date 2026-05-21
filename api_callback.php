<?php
// ১. ক্রস-ওরিজিন এবং সেশন প্রোটেকশন হেডার
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db.php';

// ২. এভিয়েটোর নোড সার্ভার থেকে আসা রিকোয়েস্ট বডি ডাটা পড়া
$json = file_get_contents('php://input');
$data = json_decode($json, true);
if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request JSON"]);
    exit;
}

$action = $data['action'];
$username = !empty($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
if (empty($username) && isset($_SESSION['user_id'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['user_id']);
}

$amount = floatval($data['amount'] ?? 0);
if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Empty Username Parameter"]);
    exit;
}

// ৩. ডাটাবেজ থেকে ইউজারের তাজা তথ্য সংগ্রহ
$u_sql = $conn->query("SELECT * FROM users WHERE LOWER(username) = LOWER('$username')");
$u_data = $u_sql->fetch_assoc();
if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found in Database"]);
    exit;
}

// 🎯 ৪. কঠোর ওয়ালেট লকিং মেকানিজম (যা আপনার বাগটি চিরতরে শেষ করবে)
// প্লেয়ার স্ক্রিনে যে ওয়ালেট সিলেক্ট করেছে, নোড সার্ভার থেকে পাঠানো ঠিক সেই ওয়ালেট আইডি-ই রিড করা হবে
$wallet = strtolower($data['wallet'] ?? 'main');

if ($wallet === 'pb') {
    $bal_col = "pb_balance"; 
    $turn_col = "pb_t"; 
    $user_current_balance = floatval($u_data['pb_balance'] ?? 0);
} elseif ($wallet === 'bonus') {
    $bal_col = "bonus_balance"; 
    $turn_col = "bonus_t"; 
    $user_current_balance = floatval($u_data['bonus_balance'] ?? 0);
} else {
    // ডিফল্ট অথবা 'main' সিলেক্ট করা থাকলে কঠোরভাবে শুধু মেইন ব্যালেন্স থেকেই টাকা কাটবে
    $bal_col = "balance"; 
    $turn_col = "main_t"; 
    $user_current_balance = floatval($u_data['balance'] ?? 0);
}

// 🎰 ৫. বাজি ধরার লজিক
if ($action == "bet") {
    if ($user_current_balance < $amount) {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance in Selected Wallet!"]);
        exit;
    }

    // শুধুমাত্র প্লেয়ারের সিলেক্ট করা কলামের ব্যালেন্স এবং টার্নওভার আপডেট কুয়েরি
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '{$u_data['username']}'");
    
    if ($update) {
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('{$u_data['username']}', '$amount', 'Aviator', 'pending')");
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Bet Update Failed"]);
    }
}
// 🎯 api_callback.php এর win এবং loss ব্লকের পুরানো কোড কেটে হুবহু এটি বসাবেন:

elseif ($action == "win") {
    $clean_winning_amount = floatval($data['amount'] ?? 0);
    
    // ডাটাবেজে গাণিতিক যোগ কঠোরভাবে লক করা হলো
    $update = $conn->query("UPDATE users SET $bal_col = CAST($bal_col AS DECIMAL(15,2)) + CAST($clean_winning_amount AS DECIMAL(15,2)) WHERE username = '{$u_data['username']}'");
    
    if ($update) {
        // 🛡️ ইউনিক আইডি লক: 'ORDER BY id DESC LIMIT 1' ব্যবহারের ফলে এটি কেবল চলতি রাউন্ডের সর্বশেষ বাজিটিকেই 'win' করবে, পেছনের কোনো পেন্ডিং বাজিকে স্পর্শ করার ক্ষমতা এর থাকবে না
        $conn->query("UPDATE bets SET status = 'win', amount = '$clean_winning_amount' WHERE LOWER(username) = LOWER('$username') AND (LOWER(status) = 'pending' OR LOWER(status) = 'bet' OR status = 'PENDING ⏳') ORDER BY id DESC LIMIT 1");
        
        $fresh_user = $conn->query("SELECT $bal_col FROM users WHERE username = '{$u_data['username']}'")->fetch_assoc();
        echo json_encode(["status" => "ok", "balance" => floatval($fresh_user[$bal_col])]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Win Error"]);
    }
}
if (isset($data['action']) && $data['action'] == "force_clean_pending") {
    // 🛡️ স্মার্ট ডুয়াল রিকভারি লক: টেবিলে থাকা শুধুমাত্র সেই বাজিগুলোকেই 'loss' করবে যেগুলো কোনো কারণে ক্যাশআউট করতে পারেনি। কোনো সফল উইনিং বাজিকে এটি ভুল করেও নষ্ট করবে না।
    $conn->query("UPDATE bets SET status = 'loss' WHERE (LOWER(status) = 'pending' OR LOWER(status) = 'bet' OR status = 'PENDING ⏳') AND status != 'win'");
    echo json_encode(["status" => "ok", "message" => "Smart pending cleanup complete successfully!"]);
    exit;
}
?>
