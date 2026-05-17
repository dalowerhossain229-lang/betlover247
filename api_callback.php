<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';
header('Content-Type: application/json');

// 🎯 ১. আইফ্রেম থেকে আসা ইনপুট ডাটা রিড ও ডিকোড করা
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request JSON"]);
    exit;
}

$action = $data['action'];

// 🎯 ২. সেশন ট্র্যাকিং ফিক্স: ডাটাবেজ সেশনের 'user_id' কী এবং এভিয়েটরের ডাটা সিঙ্ক
$username = !empty($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
if (empty($username) && isset($_SESSION['user_id'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['user_id']);
}

$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Aviator');

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Empty Username Parameter"]);
    exit;
}

// 🎯 ৩. ডাটাবেজ থেকে ইউজারের সঠিক তথ্য আনা (Case-Insensitive)
$u_sql = $conn->query("SELECT * FROM users WHERE LOWER(username) = LOWER('$username')");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found in DB for " . $username]);
    exit;
}

// 🎯 ৪. আপনার ডাটাবেজ কলামের নামের সাথে মিল রেখে ৩টি ওয়ালেটের স্মার্ট অটো-ডিটেক্টর
$pb_bal = floatval($u_data['pb_balance'] ?? 0);
$bonus_bal = floatval($u_data['bonus_balance'] ?? 0);
$main_bal = floatval($u_data['balance'] ?? 0);

if ($pb_bal >= $amount) {
    $bal_col = "pb_balance"; $turn_col = "pb_t";
    $user_current_balance = $pb_bal;
} elseif ($bonus_bal >= $amount) {
    $bal_col = "bonus_balance"; $turn_col = "bonus_t";
    $user_current_balance = $bonus_bal;
} else {
    $bal_col = "balance"; $turn_col = "t_main";
    $user_current_balance = $main_bal;
}

// 🎰 ৫. বাজি ধরার লজিক
if ($action == "bet") {
    if ($user_current_balance < $amount) {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance!"]);
        exit;
    }

    // সঠিক ওয়ালেটের ব্যালেন্স এবং টার্নওভার আপডেট কুয়েরি
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '$username'");
    
    if ($update) {
        // আপনার টেবিলের ডিফল্ট 'pending' স্ট্যাটাস অনুযায়ী বাজি ইনসার্ট
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$username', '$amount', 'Aviator', 'pending')");
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 💰 ৬. ক্যাশআউট বা জেতার লজিক
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    
    if ($update) {
        // 'status' এর মান 'pending' চেক করে সেটিকে সাথে সাথে 'win' করে দেওয়ার কুয়েরি
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '$username' AND status = 'pending'");
        $new_balance = $user_current_balance + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔴 ৭. লস লজিক (ক্রাশ খেলে হিস্ট্রি আপডেট হবে)
elseif ($action == "loss") {
    // 'status' এর মান 'pending' চেক করে সেটিকে সাথে সাথে 'loss' করে দেওয়ার কুয়েরি
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '$username' AND status = 'pending'");
    echo json_encode(["status" => "ok", "message" => "Loss Recorded"]);
}
?>
