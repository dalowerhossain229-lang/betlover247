<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Wallet & Turnover Column Fixer</h2>";

// প্রতিটি কলাম আলাদাভাবে চেক করে তৈরি করার ফাংশন
function addColumn($conn, $column, $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$column'");
    if ($check->num_rows == 0) {
        $sql = "ALTER TABLE users ADD $column $type";
        if ($conn->query($sql)) {
            echo "✅ Column '$column' added successfully.<br>";
        } else {
            echo "❌ Error adding '$column': " . $conn->error . "<br>";
        }
    } else {
        echo "ℹ️ Column '$column' already exists.<br>";
    }
}

// কলামগুলো তৈরি করা শুরু
addColumn($conn, 'pb_balance', 'DECIMAL(10,2) DEFAULT 0.00');
addColumn($conn, 'bonus_balance', 'DECIMAL(10,2) DEFAULT 0.00');
addColumn($conn, 'main_t', 'DECIMAL(10,2) DEFAULT 0.00');
addColumn($conn, 'pb_t', 'DECIMAL(10,2) DEFAULT 0.00');
addColumn($conn, 'bonus_t', 'DECIMAL(10,2) DEFAULT 0.00');

// ব্যালেন্স কলাম আপডেট
$conn->query("ALTER TABLE users MODIFY balance DECIMAL(10,2) DEFAULT 0.00");

// টেস্ট ব্যালেন্স এড করা (Abid123 এর জন্য)
$conn->query("UPDATE users SET pb_balance = 500.00, bonus_balance = 200.00 WHERE username = 'Abid123'");

echo "<br><p style='color:#00ff88;'>কাজ শেষ! এখন গেম পেজে গিয়ে PB বা Bonus দিয়ে চেক করুন।</p>";
echo "<a href='play.php' style='color:#ffdf1b; font-weight:bold;'>গেম পেজে ফিরে যান</a>";
echo "</body>";
?>
