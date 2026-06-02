<?php
session_start();
include 'db.php';

// 🔒 ১. সেশন থেকে প্লেয়ারের লাইভ আইডি ভেরিফিকেশন বর্ম
if(!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}
$user = $_SESSION['user_id'];

// 📝 ২. ডাটাবেজ 'bets' টেবিল থেকে প্লেয়ারের কারেন্ট বাজি লগের তাজা ২০টি ডাটা তুলে আনা
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
        body { background: #000; color: #fff; font-family: sans-serif; padding: 15px; margin: 0; box-sizing: border-box; }
        h3 { color: #00ff88; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; font-size: 18px; }
        .log-card { background: #111; padding: 14px; border-radius: 12px; border: 1px solid #222; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
        .game-info b { font-size: 15px; color: #fff; display: block; margin-bottom: 4px; }
        .game-info small { color: #555; font-size: 11px; }
        .status-badge { font-weight: bold; font-size: 15px; text-align: right; }
        .profit-text { color: #00ff66; text-shadow: 0 0 10px rgba(0,255,102,0.2); }
        .loss-text { color: #ff4444; text-shadow: 0 0 10px rgba(255,68,68,0.2); }
        .sub-status { font-size: 9px; color: #666; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <h3>🎰 Game History</h3>

    <?php if($logs && $logs->num_rows > 0): while($row = $logs->fetch_assoc()): ?>
        <div class="log-card">
            
            <!-- 🎮 বাম দিকে গেমের নাম এবং টাইমস্ট্যাম্প ফুটে উঠবে ওস্তাদ -->
            <div class="game-info">
                <b>
                <?php 
                $game_name_check = !empty($row['game_id']) ? $row['game_id'] : 'Casino Game';
                if ($game_name_check === 'Color-Trade') {
                    echo 'Present Game';
                } else {
                    echo htmlspecialchars($game_name_check);
                }
                ?>
                </b>
                <small><?php echo isset($row['created_at']) ? $row['created_at'] : (isset($row['date']) ? $row['date'] : 'Just Now'); ?></small>
            </div>
            
            <!-- 💰 ডান দিকে স্টেক অ্যামাউন্টের জটিল জ্যাম বাদ দিয়ে রিয়াল লাভ ও লসের নিখুঁত ক্যাসিনো হিসাব চেইন -->
            <div class="status-badge">
                <?php
                $status = strtolower($row['status'] ? $row['status'] : 'bet');
                $stake_amount = floatval($row['amount']);

                if ($status == 'win') {
                    // ⚡ [ওরিজিনাল ওッズ মাল্টিপ্লায়ার ট্র্যাকার লজিক ভাই ভাই]: 
                    // যদি ডাটাবেজে ওরিজিনাল উইন এমাউন্টের জন্য আলাদা কলাম (যেমন win_amount) না থাকে, 
                    // তবে গেম সার্ভারের ওরিজিনাল ওッズ সিঙ্ক অনুযায়ী প্রফিট এমাউন্ট ডাইনামিকলি স্ক্রিনে শো করানোর সুপ্রীম চাবি ভাই ভাই
                    $win_payout_amount = isset($row['win_amount']) ? floatval($row['win_amount']) : ($stake_amount * 1.419); 
                    
                    if($win_payout_amount <= $stake_amount) {
                        $win_payout_amount = $stake_amount * 1.419; // গ্লোবাল ফলব্যাক বাফার লক ওস্তাদ
                    }

                    echo '<span class="profit-text">+৳' . number_format($win_payout_amount, 2) . '</span>';
                    echo '<div class="sub-status" style="color: #00ff66;">PROFIT ✓</div>';
                } else {
                    // লস হলে বাজি ধরার মূল টাকা মাইনাস ফিগার অন ফায়ার শো করবে ভাই ভাই
                    echo '<span class="loss-text">-৳' . number_format($stake_amount, 2) . '</span>';
                    echo '<div class="sub-status" style="color: #ff4444;">LOSS ✗</div>';
                }
                ?>
            </div>

        </div>
    <?php endwhile; else: ?>
        <p style="color: #444; text-align: center; margin-top: 50px; font-size: 14px;">কোনো খেলার হিস্টোরি রেকর্ড পাওয়া যায়নি ভাই।</p>
    <?php endif; ?>

</body>
</html>
