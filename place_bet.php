<?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
$bet = isset($_POST['amount']) ? (float)$_POST['amount'] : 10; // বাজি ধরার টাকা
$wallet = isset($_POST['wallet']) ? $_POST['wallet'] : 'main'; // ইউজার যা সিলেক্ট করেছে

if (empty($u)) {
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

$conn->query("INSERT INTO bets (username, bet_amount, win_amount, balance_type, game_name, status) VALUES ('$u', '$bet', 0, '$wallet', '2048 Game', 'pending')");


    echo "success"; // এটি পেলে আপনার গেমে পপআপ আসবে
} else {
    echo "error_db";
}
ob_end_flush();
?>
