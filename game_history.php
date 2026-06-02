<?php
session_start();
include "db.php";

// ১. সেশন চেক এবং মেম্বার লগইন সিকিউরিটি ভ্যালিডেশন
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// ২. মেম্বারের ইউজারনেম তুলে এনে 'bets' টেবিল থেকে হিস্টোরি রিড করা
$logs = $conn->query("SELECT * FROM bets WHERE username = (SELECT username FROM users WHERE id = '$u_id') ORDER BY id DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Game History</title>
    <style>
        /* 🎰 BETLOVER77 / OWNER অফিসিয়াল গেম কার্ড ডিজাইন */
        body { background: #000; color: #fff; font-family: sans-serif; padding: 15px; margin: 0; box-sizing: border-box; }
        h3 { color: #00FF88; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; font-size: 18px; }
        .log-card { background: #111; padding: 14px; border-radius: 12px; border: 1px solid #222; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
        .game-info b { font-size: 15px; color: #fff; display: block; margin-bottom: 4px; text-transform: capitalize; }
        .game-info small { color: #666; font-size: 11px; }
        .status-badge { font-weight: bold; font-size: 15px; text-align: right; }
        .profit-text { color: #00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.2); }
        .loss-text { color: #ff4444; text-shadow: 0 0 10px rgba(255,68,68,0.2); }
        .sub-status { font-size: 9px; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <h3>📊 Game History</h3>

    <?php 
    if ($logs && $logs->num_rows > 0): 
        while ($row = $logs->fetch_assoc()): 
    ?>
        <div class="log-card">
            
            <!-- বাম পাশ: গেমের নাম এবং ডেট/টাইম ডিসপ্লে -->
            <div class="game-info">
                <b>
                    <?php 
                    // ডাটাবেজ থেকে গেমের নাম প্রপারলি ফিল্টার করা হচ্ছে
                    $game_name = !empty($row['game_id']) ? $row['game_id'] : 'Casino Game';
                    
                    if ($game_name === 'Color-Trade') {
                        echo 'Present Game';
                    } else {
                        echo htmlspecialchars($game_name);
                    }
                    ?>
                </b>
                <small>
                    📅 <?php echo isset($row['created_at']) ? $row['created_at'] : (isset($row['date']) ? $row['date'] : 'Just Now'); ?>
                </small>
            </div>

            <!-- ডান পাশ: গেমের প্রফিট/লস (টাকা কাটা ও যোগ হওয়ার সঠিক হিসেব) -->
            <div class="status-badge">
                <?php 
                $status = strtolower(isset($row['status']) ? $row['status'] : 'pending');
                $stake_amount = floatval($row['amount']);
                $win_amount = isset($row['win_amount']) ? floatval($row['win_amount']) : 0;

                if ($status === 'win') {
                    // যদি উইন হয়, তবে ডাটাবেজের win_amount দেখাবে (খালি থাকলে ব্যাকআপ হিসেবে স্টেক দেখাবে)
                    $display_profit = ($win_amount > 0) ? $win_amount : $stake_amount;
                    echo '<span class="profit-text">+' . number_format($display_profit, 2) . ' ৳</span>';
                    echo '<div class="sub-status" style="color: #00ff88;">WIN</div>';
                } elseif ($status === 'pending') {
                    // পেন্ডিং বেটের ক্ষেত্রে কেবল স্টেক অ্যামাউন্ট দেখাবে
                    echo '<span style="color: #ffaa00;">' . number_format($stake_amount, 2) . ' ৳</span>';
                    echo '<div class="sub-status" style="color: #ffaa00;">PENDING</div>';
                } else {
                    // লস হলে বেটিং অ্যামাউন্ট মাইনাস চিহ্নে দেখাবে
                    echo '<span class="loss-text">-' . number_format($stake_amount, 2) . ' ৳</span>';
                    echo '<div class="sub-status" style="color: #ff4444;">LOST</div>';
                }
                ?>
            </div>

        </div>
    <?php 
        endwhile; 
    else: 
    ?>
        <p style="color: #444; text-align: center; margin-top: 50px; font-size: 14px;">আপনার খেলার হিস্টোরি রেকর্ড পাওয়া যায়নি।</p>
    <?php 
    endif; 
    ?>

</body>
</html>
