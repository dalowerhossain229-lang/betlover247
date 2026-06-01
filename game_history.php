<?php
session_start();
include 'db.php';

// 🔒 ১. সেশন থেকে প্লেয়ারের লাইভ আইডি ভেরিফিকেশন বর্ম
if(!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}
$user = $_SESSION['user_id'];

// 📝 ২. ডাটাবেজ 'bets' টেবিল থেকে প্লেয়ারের কারেন্ট বাজি লগের তাজা ২০টি ডাটা তুলে আনা (api_callback ও betlogs এর সাথে একুরেট সিঙ্ক)
$logs = $conn->query("SELECT * FROM bets WHERE username = '$user' ORDER BY id DESC LIMIT 20");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Game History</title>
    <style>
        /* 🎰 [BETLOVER777 / DKWIN ওরিজিনাল লবি ডার্ক হিস্টোরি থিম সিঙ্ক ভাই ভাই] */
        body { background: #000; color: #fff; font-family: sans-serif; padding: 15px; margin: 0; }
        .log-card { background: #111; padding: 12px; border-radius: 8px; border: 1px solid #333; margin-bottom: 10px; }
        .bet-status { font-weight: bold; padding: 4px 10px; border-radius: 4px; font-size: 13px; }
        .win { background: rgba(0, 255, 102, 0.15); color: #00ff66; }
        .loss { background: rgba(255, 68, 68, 0.15); color: #ff4444; }
        .pending { background: rgba(255, 204, 0, 0.15); color: #ffcc00; }
    </style>
</head>
<body>

    <h3>🎰 Game History</h3>

    <?php if($logs && $logs->num_rows > 0): while($row = $logs->fetch_assoc()): ?>
        <div class="log-card">
            <div>
                <!-- 🕒 রাউন্ডের রিয়েল-টাইম কাউন্টার বা টাইমস্ট্যাম্প -->
                <small><?php echo isset($row['created_at']) ? $row['created_at'] : (isset($row['date']) ? $row['date'] : 'Just Now'); ?></small><br>
                
                <!-- 🎯 [ম্যাজিক কিলার ডাইনামিক নোড]: bets টেবিলের game_id কলামে সেভ হওয়া আসল গেমের নাম ওয়ান-শটে ক্যাচ করবে ভাই -->
                <b><?php echo htmlspecialchars(!empty($row['game_id']) ? $row['game_id'] : 'Casino Game'); ?></b>
            </div>
            <div style="margin-top: 8px;">
                <span style="margin-right: 15px; color: #aaa;">Stake: <b>৳<?php echo number_format($row['amount'], 2); ?></b></span>
                
                <?php
                // 🔌 [WIN-LOSS STATUS ডাইনামিক ইন্টারсеপ্টর বর্ম ভাই ভাই]
                $status = strtolower($row['status'] ? $row['status'] : 'bet');
                if ($status == 'win') {
                    echo '<span class="bet-status win">WIN ✓</span>';
                } elseif ($status == 'loss' || $status == 'lose') {
                    echo '<span class="bet-status loss">LOSS ✗</span>';
                } else {
                    echo '<span class="bet-status pending">PENDING ⧗</span>';
                }
                ?>
            </div>
        </div>
    <?php endwhile; else: ?>
        <p style="color: #555; text-align: center; margin-top: 50px;">কোনো খেলার হিস্টোরি রেকর্ড পাওয়া যায়নি ভাই।</p>
    <?php endif; ?>

</body>
</html>
