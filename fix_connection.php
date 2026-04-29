<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Database Connection & Table Fixer</h2>";

// ১. গেম হিস্টোরি টেবিল তৈরি করার কুয়েরি
$sql = "CREATE TABLE IF NOT EXISTS game_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    game_name VARCHAR(100) DEFAULT '2048 Game',
    wallet_type VARCHAR(20) DEFAULT 'main',
    bet_amount DECIMAL(10,2) NOT NULL,
    win_loss DECIMAL(10,2) DEFAULT 0.00,
    status VARCHAR(20) DEFAULT 'Completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql)) {
    echo "<p style='color:#00ff88;'>✅ Success! 'game_history' টেবিলটি ডাটাবেসে তৈরি করা হয়েছে।</p>";
    echo "<p>এখন বাটন ক্লিক করলে আর 'কানেকশন এরর' আসবে না।</p>";
    echo "<br><a href='play.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>গেম পেজে ফিরে যান</a>";
} else {
    echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
}

echo "</body>";
?>
