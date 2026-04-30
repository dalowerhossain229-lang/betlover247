<?php
include 'db.php';

echo "<h2>🚀 অ্যাফিলিয়েট সিস্টেম রিসেট হচ্ছে...</h2>";

// পুরনো কলামগুলো থাকলে মুছে ফেলা (যাতে এরর না আসে)
$conn->query("ALTER TABLE users DROP COLUMN IF EXISTS is_affiliate");
$conn->query("ALTER TABLE users DROP COLUMN IF EXISTS ref_code");
$conn->query("ALTER TABLE users DROP COLUMN IF EXISTS ref_by");
$conn->query("ALTER TABLE users DROP COLUMN IF EXISTS aff_balance");
$conn->query("ALTER TABLE users DROP COLUMN IF EXISTS monthly_stats");

// নতুন ফ্রেশ কলাম যোগ করা
$sql = "ALTER TABLE users 
    ADD COLUMN is_affiliate INT DEFAULT 0, 
    ADD COLUMN ref_code VARCHAR(20) UNIQUE, 
    ADD COLUMN ref_by VARCHAR(50), 
    ADD COLUMN aff_balance DECIMAL(10,2) DEFAULT 0.00,
    ADD COLUMN player_loss_profit DECIMAL(10,2) DEFAULT 0.00";

if ($conn->query($sql)) {
    echo "<p style='color:green;'>✅ ডাটাবেস এখন ৫% কমিশন ও ৪৭% শেয়ার লজিকের জন্য রেডি!</p>";
} else {
    echo "<p style='color:red;'>❌ ভুল: " . $conn->error . "</p>";
}
?>
