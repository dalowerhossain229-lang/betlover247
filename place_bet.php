<?php
ob_start();
session_start();
include 'db.php';

// 🔒 ১. সেশন থেকে নিখুঁতভাবে ইউজারের আইডি চেক
if (isset($_SESSION['user_id'])) {
    $u = $_SESSION['user_id'];
} elseif (isset($_SESSION['username'])) {
    $u = $_SESSION['username'];
} else {
    die("error_no_session");
}

// 🎰 ২. ফ্রন্টএন্ড গেটওয়ে থেকে আসা বাজি, ওয়ালেট এবং সুনির্দিষ্ট গেমের নাম ডাইনামিক ক্যাচ করা
$bet = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$wallet = isset($_POST['wallet']) ? strtoupper($_POST['wallet']) : 'MAIN';

// 🎯 [গেম নেম ইন্টারসেপ্টর বর্ম]: লবি থেকে পাঠানো ওরিজিনাল গেমের নাম ক্যাচ করার মূল মাস্টার চাবি ভাই ভাই
$dynamic_game_name = !empty($_POST['game']) ? mysqli_real_escape_string($conn, $_POST['game']) : 'Aviator';

// 🎛️ ৩. আপনার ডাটাবেজ কলামের নামের সাথে মিল রেখে ওয়ালেট কন্ডিশন ফিল্টার
$bal_col = "balance";
$turn_col = "t_main";

if ($wallet === 'PB') {
    $bal_col = "pb_balance";
    $turn_col = "pb_t";
} elseif ($wallet === 'BONUS') {
    $bal_col = "bonus_balance";
    $turn_col = "bonus_t";
}

// 💰 ৪. ডাটাবেজ থেকে রিয়েল টাইম কারেন্ট ব্যালেন্স চেক করা
$check = $conn->query("SELECT $bal_col FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $check->fetch_assoc();

if (!$user_data || floatval($user_data[$bal_col]) < $bet) {
    die("insufficient_balance"); // টাকা না থাকলে বাজি ধরতে দেবে না ভাই
}

// 🛫 ৫. ব্যালেন্স কাট আউট এবং ইউজার লগের সেন্ট্রাল কোয়েরি লুপ
$sql = "UPDATE users SET $bal_col = $bal_col - $bet, $turn_col = $turn_col + $bet WHERE username = '$u' OR id = '$u'";

if ($conn->query($sql)) {
    // 📝 [মেগা ডাইনামিক নোড]: 'Aviator' কেটে সরাসরি $dynamic_game_name বসানো হয়েছে যাতে ওরিজিনাল গেমের নাম সেভ হয় ভাই
    $stmt = $conn->prepare("INSERT INTO bets (username, amount, game_id, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("sds", $u, $bet, $dynamic_game_name);
    
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
