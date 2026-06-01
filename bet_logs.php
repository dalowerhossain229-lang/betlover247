<?php
session_start();
include 'header.php';
include 'db.php';

// 🔒 ১. সেশন থেকে প্লেয়ারের ওরিজিনাল লাইভ আইডি চেক
if(!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}
$u = $_SESSION['user_id'];

// 📝 ২. ডাটাবেজ 'bets' টেবিল থেকে প্লেয়ারের রিয়াল গেমের নাম ও বাজির তাজা ৩০টি ডাটা তুলে আনা
$bet_query = "SELECT * FROM bets WHERE username = '$u' ORDER BY id DESC LIMIT 30";
$bet_res = $conn->query($bet_query);
?>

<div style="padding: 20px; color: white; font-family: sans-serif; min-height: 80vh;">
    <h3 style="color: #00ff88; text-align: center; margin-bottom: 20px; text-transform: uppercase;">🎰 API Game Logs</h3>

    <?php if($bet_res && $bet_res->num_rows > 0): while($row = $bet_res->fetch_assoc()): ?>
        <div style="background: #111; border: 1px solid #222; padding: 15px; border-radius: 12px; margin-bottom: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 5px;">
                    <span style="background: #333; color: #fff; font-size: 9px; padding: 2px 6px; border-radius: 4px;">🎮</span>
                    <!-- 🎯 [মেগা কিলার ডাইনামিক নোড]: ডাটাবেজের game_id কলামের ভেতর সেভ হওয়া আসল গেমের নাম ওয়ান-শটে ক্যাচ করবে ভাই -->
                    <span style="font-size: 14px; font-weight: bold; color: #00ff88;"><?php echo htmlspecialchars(!empty($row['game_id']) ? $row['game_id'] : 'Casino Game'); ?></span>
                </div>
                
                <!-- 🕒 রাউন্ডের রিয়েল-টাইম টাইমস্ট্যাম্প এন্ট্রি ভাই ভাই -->
                <small style="color: #666; font-size: 10px;"><?php echo isset($row['date']) ? $row['date'] : 'Just Now'; ?></small>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                <span style="font-size: 14px; color: #fff;">Stake: <b>৳<?php echo number_format($row['amount'], 2); ?></b></span>
                
                <div style="text-align: right;">
                    <?php
                    // 🔌 [WIN-LOSS STATUS ডাইনামিক ইন্টারсеপ্টর বর্ম ভাই ভাই]
                    $status = strtolower($row['status'] ? $row['status'] : 'bet');
                    if ($status == 'win') {
                        echo '<span style="color: #00ff88; font-weight: bold; font-size: 14px;">৳' . number_format($row['amount'], 2) . '</span>';
                        echo '<div style="font-size: 9px; color: #00ff88; text-transform: uppercase; margin-top: 3px;">WIN ✓</div>';
                    } else {
                        echo '<span style="color: #ff4444; font-weight: bold; font-size: 14px;">৳0.00</span>';
                        echo '<div style="font-size: 9px; color: #ff4444; text-transform: uppercase; margin-top: 3px;">LOSS ✗</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endwhile; else: ?>
        <div style="text-align: center; margin-top: 100px; color: #555;">
            <p>আপনার কোনো গেম হিস্টোরি পাওয়া যায়নি ভাই।</p>
            <a href="index.php" style="color: #00ff88; text-decoration: none; font-size: 14px;">← লবিতে ফিরে যান ভাই</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
