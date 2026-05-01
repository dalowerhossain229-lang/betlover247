<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Game Bet History Fixer</h2>";

// ১. টেবিলটি আছে কি না চেক করা
$table_check = $conn->query("SHOW TABLES LIKE 'bets'");

if ($table_check->num_rows == 0) {
    // ২. টেবিল না থাকলে তৈরি করা
    $sql = "CREATE TABLE bets (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        game_name VARCHAR(100) DEFAULT '2048',
        bet_amount DECIMAL(10,2) NOT NULL,
        win_amount DECIMAL(10,2) DEFAULT 0.00,
        win_loss ENUM('win', 'loss', 'pending') DEFAULT 'pending',
        active_wallet VARCHAR(20) DEFAULT 'main',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql)) {
        echo "<p style='color:#00ff88;'>✅ 'bets' টেবিল সফলভাবে তৈরি করা হয়েছে।</p>";
    } else {
        echo "<p style='color:red;'>❌ এরর: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:#ffdf1b;'>ℹ️ 'bets' টেবিলটি আগে থেকেই ডাটাবেসে আছে।</p>";
}

echo "<h3 style='color:#00ff88;'>🚀 এখন আপনার গেমের সব বেট হিস্টোরি এখানে জমা হবে!</h3>";
echo "</body>";
?>
