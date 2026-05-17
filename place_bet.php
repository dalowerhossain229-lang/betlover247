<?php
ob_start();
session_start();
include 'db.php';

// 🎯 ১. সেশন থেকে নিখুঁতভাবে ইউজার আইডি চেক
if (isset($_SESSION['user_id'])) {
    $u = $_SESSION['user_id'];
} elseif (isset($_SESSION['username'])) {
    $u = $_SESSION['username'];
} else {
    die("error_no_session");
}

// ফ্রন্টএন্ড থেকে আসা বাজি এবং ওয়ালেটের ডাটা নেওয়া
$bet = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$wallet = isset($_POST['wallet']) ? strtolower($_POST['wallet']) : 'main';

// 🎯 ২. আপনার ডাটাবেজ কলামের নামের সাথে মিল রেখে ওয়ালেট কন্ডিশন ফিক্সড
if ($wallet == 'pb') {
    $bal_col = "pb_balance";
    $turn_col = "pb_t";
} elseif ($wallet == 'bonus') {
    $bal_col = "bonus_balance";
    $turn_col = "bonus_t";
} else {
    $bal_col = "balance";
    $turn_col = "t_main"; // 🔗 আপনার ডাটাবেজের আসল কলামের নাম t_main
}

// 🎯 ৩. ডাটাবেজ থেকে রিয়েল-টাইম কারেন্ট ব্যালেন্স চেক করা
$check = $conn->query("SELECT $bal_col FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $check->fetch_assoc();

if (!$user_data || floatval($user_data[$bal_col]) < $bet) {
    die("insufficient_balance"); // টাকা না থাকলে বাজি ধরতে দেবে না
}

// 🎯 ৪. ব্যালেন্স কাটা এবং টার্নওভার যোগ করার মূল কুয়েরি
$sql = "UPDATE users SET $bal_col = $bal_col - $bet, $turn_col = $turn_col + $bet WHERE username = '$u' OR id = '$u'";

if ($conn->query($sql)) {
    
    // 🎯 ৫. আপনার bets টেবিলের আসল কলাম অনুযায়ী প্রিপেয়ার্ড স্টেটমেন্ট ফিক্সড
    $stmt = $conn->prepare("INSERT INTO bets (username, amount, game_id, status) VALUES (?, ?, 'Aviator', 'pending')");
    $stmt->bind_param("sd", $u, $bet);
    
    if ($stmt->execute()) {
        echo "success"; // বাজি সফল হলে এটি মেইন ড্যাশবোর্ডে পাঠাবে
    } else {
        echo "error_db";
    }
    $stmt->close();
} else {
    echo "error_db";
}

ob_end_flush();
?>
