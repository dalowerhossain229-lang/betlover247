<?php
include 'db.php';

// ১. গেম ট্রানজ্যাকশন টেবিল (বেট এবং উইন এর হিসেব রাখার জন্য)
$sql1 = "CREATE TABLE IF NOT EXISTS game_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    game_name VARCHAR(100),
    action ENUM('bet', 'win', 'refund') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    tx_id VARCHAR(255) UNIQUE NOT NULL, -- এপিআই থেকে আসা ইউনিক আইডি
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// ২. ইউজার ব্যালেন্স কলাম আপডেট (যদি আগে ডেসিমেল না থাকে)
$sql2 = "ALTER TABLE users MODIFY COLUMN balance DECIMAL(10, 2) DEFAULT 0.00";

// ৩. সেটিংস টেবিলে এপিআই কী স্টোর করার জন্য কলাম (ঐচ্ছিক কিন্তু জরুরি)
$sql3 = "ALTER TABLE settings ADD COLUMN api_key VARCHAR(255), ADD COLUMN operator_id VARCHAR(100)";

if ($conn->query($sql1) && $conn->query($sql2)) {
    echo "<h1>✅ Game Database Ready!</h1>";
    echo "<p>গেম ট্রানজ্যাকশন টেবিল তৈরি হয়েছে এবং ব্যালেন্স ফরম্যাট আপডেট হয়েছে।</p>";
} else {
    echo "এরর: " . $conn->error;
}
?>
