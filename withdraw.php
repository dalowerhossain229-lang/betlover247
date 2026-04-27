<?php
session_start();
include 'db.php';
$u = $_SESSION['username'] ?? '';

// ১. ডাটাবেস থেকে তথ্য আনা
$query = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user = $query->fetch_assoc();

$done = (float)($user['main_t'] ?? 0);
$target = (float)($user['t_main'] ?? 1000);
?>

<body style="background:#000; color:#fff; text-align:center; font-family:sans-serif; padding:20px;">
    <h2>💰 WITHDRAW</h2>
    
    <!-- ব্যালেন্স -->
    <div style="border:1px solid #07ff88; padding:15px; border-radius:10px; margin-bottom:20px;">
        <p style="color:#888;">Current Balance</p>
        <h2 style="color:#ffdf1b;">৳ <?php echo number_format($user['balance'] ?? 0, 2); ?></h2>
    </div>

    <!-- টার্নওভার চেক -->
    <?php if ($done < $target): ?>
        <div style="border:1px solid #ff4d4d; padding:20px; border-radius:10px; background:rgba(255,0,0,0.1);">
            <p style="color:#ff4d4d; font-weight:bold;">⚠️ টার্নওভার অসম্পূর্ণ!</p>
            <p style="font-size:14px;">প্রগ্রেস: <?php echo "$done / $target"; ?></p>
            <div style="width:100%; background:#222; height:8px; border-radius:5px; margin-top:10px;">
                <?php $percent = ($target > 0) ? ($done / $target) * 100 : 0; ?>
                <div style="width:<?php echo $percent; ?>%; background:#ff4d4d; height:100%; border-radius:5px;"></div>
            </div>
        </div>
    <?php else: ?>
        <!-- টার্নওভার শেষ হলে এই মেসেজ আসবে -->
        <div style="border:1px solid #00ff88; padding:20px; border-radius:10px;">
            <p style="color:#00ff88;">✅ আপনি উইথড্র দিতে পারবেন!</p>
            <p>উইথড্র ফর্মটি আমরা পরের ধাপে যোগ করবো।</p>
        </div>
    <?php endif; ?>

</body>
