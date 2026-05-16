<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

// 🎯 ইউজারনেম প্যারামিটার ক্যাচ করা
$username = !empty($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
if (empty($username) && isset($_SESSION['username'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
}

$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Aviator');

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Empty Username Parameter"]);
    exit;
}

// ২. ডাটাবেজ থেকে রিয়েল-টাইমে ইউজারের ওয়ালেটের সর্বশেষ তথ্য আনা
$u_sql = $conn->query("SELECT * FROM users WHERE LOWER(username) = LOWER('$username')");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found In DB"]);
    exit;
}

// 🎯 ৩টি ওয়ালেটের রিয়েল-টাইম কলাম ট্র্যাকিং (আপনার ডাটাবেজের সঠিক নাম অনুযায়ী ফিক্সড)
$wallet = strtolower($u_data['wallet'] ?? 'main');


if ($wallet == 'pb' || $wallet == 'promo') {
    $bal_col = "pb_balance"; 
    $turn_col = "pb_t";
} elseif ($wallet == 'bonus') {
    $bal_col = "bonus_balance"; 
    $turn_col = "bonus_t";
} else {
    $bal_col = "balance"; 
    $turn_col = "main_t";
}

// ইউজারের ওই নির্দিষ্ট ওয়ালেটের বর্তমান আসল ব্যালেন্স
$user_current_balance = floatval($u_data[$bal_col] ?? 0);

// 🎰 বাজি ধরার লজিক
if ($action == "bet") {
    // অ্যান্টি-ডাবল ক্লিক প্রোটেকশন
    $check_dup = $conn->query("SELECT id FROM bets WHERE username = '$username' AND amount = '$amount' AND status = 'bet' AND created_at >= NOW() - INTERVAL 2 SECOND LIMIT 1");
    if ($check_dup && $check_dup->num_rows > 0) {
        echo json_encode(["status" => "ok", "message" => "Duplicate Bypass", "balance" => $user_current_balance]);
        exit;
    }

    if ($user_current_balance < $amount) {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance!"]);
        exit;
    }

    // সঠিক ওয়ালেটের ব্যালেন্স আপডেট কুয়েরি
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '$username'");
    
    if ($update) {
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$username', '$amount', 'Aviator', 'bet')");
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 💰 ক্যাশআউট বা জেতার লজিক
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    
    if ($update) {
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '$username' AND status = 'bet'");
        $new_balance = $user_current_balance + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔴 লস লজিক
elseif ($action == "loss") {
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '$username' AND status = 'bet'");
    echo json_encode(["status" => "ok", "message" => "Loss Recorded"]);
}
?>
