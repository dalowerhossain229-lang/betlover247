<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit; 
}

$user = $_SESSION['user_id'];
$game_id = $_GET['id'] ?? 'default';

// এখানে API প্রোভাইডারের কাছে রিকোয়েস্ট পাঠিয়ে গেম লিঙ্ক আনতে হয়
// আপাতত আমরা একটি ডামি ফ্রেম তৈরি করছি যাচাই করার জন্য
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playing Game</title>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; background: #000; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>
    <!-- এখানে API থেকে আসা লিঙ্কটি বসবে -->
    <iframe src="https://example-game-provider.com<?php echo $user; ?>&game=<?php echo $game_id; ?>"></iframe>
</body>
</html>
