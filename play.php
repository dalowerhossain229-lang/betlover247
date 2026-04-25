<?php
// ১. সবার আগে পিএইচপি ট্যাগ শুরু করতে হবে
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ২. এরপর সেশন এবং ডাটাবেস কানেক্ট করতে হবে
session_start();
include 'db.php';

// ১. ইউজার লগইন আছে কি না চেক করা
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION['user_id'];
$game_id = $_GET['id'] ?? 'default_game'; // গেমের আইডি (যেমন: super-ace)

// ২. এপিআই প্রোভাইডার থেকে গেম লিঙ্ক আনার ফাংশন (স্যাম্পল)
// এখানে আপনার প্রোভাইডারের দেওয়া API Call বসাতে হবে
// আপাতত আমরা একটি ডামি প্রিভিউ দেখাচ্ছি
// ২৩ নম্বর লাইন থেকে এই কোডটি শুরু হবে
$api_url = "api_callback.php";

$post_data = json_encode([
    "action" => "getBalance",
    "username" => $user,
    "amount" => 0,
    "tx_id" => "TEST_".time()
]);

$opts = ['http' => ['method' => 'POST', 'header' => 'Content-type: application/json', 'content' => $post_data]];
$context = stream_context_create($opts);
// আপনার সাইটের ডোমেইন অনুযায়ী ব্যালেন্স রিকোয়েস্ট পাঠানো
$response = json_decode(@file_get_contents("http://".$_SERVER['HTTP_HOST']."/".$api_url, false, $context), true);

$current_balance = $response['balance'] ?? 0;
$game_url = "https://2048.org"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Play Game - BETLOVER777</title>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; background: #000; font-family: sans-serif; }
        .game-header { height: 50px; background: #111; display: flex; align-items: center; padding: 0 15px; border-bottom: 1px solid #333; }
        .back-btn { color: #00ff88; text-decoration: none; font-weight: bold; font-size: 14px; }
        .game-frame { width: 100%; height: calc(100% - 51px); border: none; }
    </style>
</head>
<body>

    <!-- গেম হেডার (ব্যাক বাটন এবং ব্যালেন্স) -->
    <div class="game-header">
        <a href="index.php" class="back-btn">⬅️ Back to Home</a>
        <div style="margin-left: auto; display: flex; align-items: center; gap: 15px;">
            <span style="color: #00ff88; font-weight: bold; font-size: 14px;">৳ <?php echo number_format($current_balance, 2); ?></span>
            <span style="color: #fff; font-size: 12px; border-left: 1px solid #333; padding-left: 10px;"><?php echo strtoupper($user); ?></span>
        </div>
    </div>


    <!-- আসল গেমটি এখানে লোড হবে -->
    <iframe src="<?php echo $game_url; ?>" class="game-frame" allowfullscreen></iframe>

</body>
</html>
