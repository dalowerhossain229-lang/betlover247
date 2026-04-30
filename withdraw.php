<?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
if (empty($u)) {
    header("Location: index.php");
    exit();
}

// ২. সরাসরি ডাটাবেস থেকে তথ্য আনা (প্রোফাইল পেজের সাথে মিল রেখে)
$query = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user_data = $query->fetch_assoc();

// ৩. টার্নওভার লজিক (প্রোফাইল পেজের কলামের সাথে মিল রেখে)
$done = (float)($user_data['main_t'] ?? 0); 
$target = (float)($user_data['t_main'] ?? 1400); // ডাটাবেস থেকে না পেলে ডিফল্ট ১৪০০

$is_turnover_done = ($done >= $target);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw</title>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; text-align: center; margin: 0; padding: 20px; }
        .card { background: #111; border: 1px solid #333; border-radius: 15px; padding: 20px; margin-top: 20px; }
        .balance-box { border: 1px solid #00ff88; padding: 20px; border-radius: 15px; margin-bottom: 20px; }
        .progress-container { background: #222; height: 12px; border-radius: 10px; width: 100%; margin: 15px 0; overflow: hidden; }
        .progress-bar { background: linear-gradient(90deg, #ff4d4d, #f00); height: 100%; border-radius: 10px; transition: width 0.5s ease; }
        .btn-play { display: inline-block; background: #00ff88; color: #000; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; margin-top: 20px; text-transform: uppercase; font-size: 13px; }
    </style>
</head>
<body>

    <h2 style="color: #00ff88; letter-spacing: 1px;">💰 WITHDRAW</h2>

    <!-- ১. ব্যালেন্স সেকশন -->
    <div class="balance-box">
        <small style="color: #888;">CURRENT BALANCE</small>
        <h1 style="color: #ffdf1b; margin: 5px 0;">৳ <?php echo number_format($user_data['balance'], 2); ?></h1>
    </div>

    <!-- ২. টার্নওভার চেক সেকশন -->
    <?php if (!$is_turnover_done): ?>
        <div class="card">
            <h3 style="color: #ffdf1b; margin-bottom: 20px;">⚠️ টার্নওভার প্রগ্রেস</h3>
            
            <div class="progress-container">
                <?php $p = ($target > 0) ? ($done / $target) * 100 : 0; ?>
                <div class="progress-bar" style="width: <?php echo min($p, 100); ?>%;"></div>
            </div>

            <p style="font-size: 16px; margin: 5px 0;">
                প্রগ্রেস: <b style="color: #fff;"><?php echo number_format($done, 0); ?></b> / <b style="color: #00ff88;"><?php echo number_format($target, 0); ?></b>
            </p>

            <p style="color: #777; font-size: 13px;">
                উইথড্র দিতে হলে আরও <b style="color: #ff4d4d;"><?php echo number_format(max(0, $target - $done), 0); ?></b> টাকার বাজি খেলা প্রয়োজন।
            </p>

            <a href="play.php" class="btn-play">🎯 খেলা চালিয়ে যান</a>
        </div>

    <?php else: ?>
        <!-- ৩. উইথড্র ফরম (টার্নওভার কমপ্লিট হলে এটি দেখাবে) -->
        <div class="card" style="border-color: #00ff88;">
            <h3 style="color: #00ff88;">✅ উইথড্র ফরম</h3>
            <p style="color: #888;">আপনার টার্নওভার সম্পন্ন হয়েছে। এখন টাকা তুলতে পারেন।</p>
            <!-- আপনার বিকাশ/নগদ ফরমের কোড এখানে আসবে -->
        </div>
    <?php endif; ?>

</body>
</html>
