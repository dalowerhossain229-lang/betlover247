<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🧹 Affiliate Data Cleaner & Reset</h2>";

// ১. পুরনো সব অ্যাফিলিয়েট কলাম মুছে ফেলা (যাতে কোনো জট না থাকে)
$old_cols = ['is_affiliate', 'ref_code', 'ref_by', 'aff_balance', 'player_loss_profit', 'monthly_stats'];

foreach ($old_cols as $col) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check->num_rows > 0) {
        $conn->query("ALTER TABLE users DROP COLUMN $col");
        echo "<p style='color:#ffa500;'>🗑️ পুরনো কলাম ডিলিট: $col</p>";
    }
}

// ২. নতুন লজিকের জন্য কলামগুলো ফ্রেশ ভাবে তৈরি করা
$sql_new = "ALTER TABLE users 
    ADD COLUMN is_affiliate INT DEFAULT 0, 
    ADD COLUMN ref_code VARCHAR(20) NULL, 
    ADD COLUMN ref_by VARCHAR(50) NULL, 
    ADD COLUMN aff_balance DECIMAL(15,2) DEFAULT 0.00,
    ADD COLUMN player_loss_profit DECIMAL(15,2) DEFAULT 0.00";

if ($conn->query($sql_new)) {
    echo "<h3 style='color:#00ff88;'>✅ ডাটাবেস রিসেট সম্পন্ন!</h3>";
    echo "<p>এখন আমরা রেজিস্ট্রেশন পেজে ৫% কমিশনের কাজ শুরু করতে পারবো।</p>";
} else {
    echo "<p style='color:red;'>❌ এরর: " . $conn->error . "</p>";
}

echo "</body>";
?>
