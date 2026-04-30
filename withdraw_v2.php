<?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
if (empty($u)) { header("Location: index.php"); exit(); }

// ২. ডাটাবেস থেকে একদম তাজা তথ্য আনা
$query = $conn->query("SELECT balance, main_t, t_main FROM users WHERE username = '$u'");
$user_data = $query->fetch_assoc();

$done = (float)($user_data['main_t'] ?? 0); 
$target = (float)($user_data['t_main'] ?? 1400); // ডাটাবেস থেকে না পেলে ডিফল্ট ১৪০০
$is_turnover_done = ($done >= $target);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw V2</title>
</head>
<body style="background: #000; color: #fff; font-family: sans-serif; text-align: center; padding: 20px;">

    <h2 style="color: #00ff88;">💰 WITHDRAW</h2>
    
    <div style="background: #111; padding: 20px; border-radius: 15px; border: 1px solid #00ff88; margin: 20px 0;">
        <small style="color: #888;">CURRENT BALANCE</small>
        <h1 style="color: #ffdf1b; margin: 5px 0;">৳ <?php echo number_format($user_data['balance'], 2); ?></h1>
    </div>

    <?php if (!$is_turnover_done): ?>
        <div style="background: #1a1a1a; padding: 20px; border-radius: 15px; border: 1px solid #333;">
            <h3 style="color: #ffdf1b;">⚠️ টার্নওভার প্রগ্রেস</h3>
            
            <?php $percent = ($target > 0) ? ($done / $target) * 100 : 0; ?>
            <div style="background: #222; height: 12px; border-radius: 10px; width: 100%; overflow: hidden; margin: 15px 0;">
                <div style="width: <?php echo min($percent, 100); ?>%; background: #ff4d4d; height: 100%;"></div>
            </div>

            <p>প্রগ্রেস: <b><?php echo number_format($done, 0); ?></b> / <b style="color: #00ff88;"><?php echo number_format($target, 0); ?></b></p>
            <p style="font-size: 12px; color: #777;">আরও ৳<?php echo number_format($target - $done, 0); ?> টাকার বাজি খেলতে হবে।</p>
            
            <a href="play.php" style="display:inline-block; margin-top:15px; background:#00ff88; color:#000; padding:10px 20px; border-radius:20px; text-decoration:none; font-weight:bold;">খেলা চালিয়ে যান</a>
        </div>
    <?php else: ?>
        <div style="background: #111; padding: 20px; border-radius: 15px; border: 1px solid #00ff88;">
            <h3 style="color: #00ff88;">✅ আপনি টাকা তুলতে পারবেন!</h3>
            <!-- এখানে আপনার উইথড্র ফরমের কোড আসবে -->
        </div>
    <?php endif; ?>

</body>
</html>
