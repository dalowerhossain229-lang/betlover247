<?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
if (isset($_SESSION['user_id'])) {
    $u = $_SESSION['user_id'];
} elseif (isset($_SESSION['username'])) {
    $u = $_SESSION['username'];
} else {
    die("error_no_session");
}
// ২. ডাটাবেসের কলাম নির্ধারণ (আপনার প্রোফাইল পেজের সাথে মিল রেখে)
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

// ৩. ডাটাবেস থেকে বর্তমান ব্যালেন্স চেক করা
$check = $conn->query("SELECT $bal_col FROM users WHERE username = '$u'");
$user_data = $check->fetch_assoc();

if (!$user_data || $user_data[$bal_col] < $bet) {
    die("insufficient_balance"); // টাকা না থাকলে বাজি ধরবে না
}

// ৪. বাজি ধরার আসল কাজ (টাকা বিয়োগ হবে এবং টার্নওভার যোগ হবে)
$sql = "UPDATE users SET 
        $bal_col = $bal_col - $bet, 
        $turn_col = $turn_col + $bet 
        WHERE username = '$u'";

if ($conn->query($sql)) {

// ৪৩ নম্বর লাইনটি এভাবে দিন
$stmt = $conn->prepare("INSERT INTO bets (username, bet_amount, win_amount, balance_type, game_name, status) VALUES (?, ?, 0, ?, '2048 Game', 'pending')");
$stmt->bind_param("sds", $u, $bet, $wallet);
$stmt->execute();


    echo "success"; // এটি পেলে আপনার গেমে পপআপ আসবে
} else {
    echo "error_db";
}
ob_end_flush();
?>
