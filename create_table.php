<?php
// create_table.php - ব্রাউজারে রান করে টেবিল ফিক্স করার স্ক্রিপ্ট
include 'db.php';

header('Content-Type: text/plain; charset=utf-8');

// উইথড্র টেবিল তৈরির এসকিউএল কুয়েরি
$sql = "CREATE TABLE IF NOT EXISTS `withdraw_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `method` VARCHAR(100) DEFAULT NULL,
  `account_number` VARCHAR(100) DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'PENDING',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "🎯 ১০০০০% সফল! 'withdraw_requests' টেবিলটি ডাটাবেজে সফলভাবে তৈরি হয়ে গেছে।\n";
    echo "এখন আপনি উইথড্র পেজে গিয়ে ফ্রেশভাবে টাকা তোলার রিকোয়েস্ট সাবমিট করতে পারবেন।";
} else {
    echo "❌ টেবিল তৈরিতে সমস্যা হয়েছে: " . $conn->error;
}
?>
