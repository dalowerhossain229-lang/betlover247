<?php
// create_table.php - ডাটাবেজে মিসিং 'number' কলাম যুক্ত করার স্ক্রিপ্ট
include 'db.php';

header('Content-Type: text/plain; charset=utf-8');

// টেবিলের ভেতর 'number' কলামটি যুক্ত করার এসকিউএল কুয়েরি
$sql = "ALTER TABLE `withdraw_requests` ADD COLUMN `number` VARCHAR(100) DEFAULT NULL AFTER `amount`;";

if ($conn->query($sql) === TRUE) {
    echo "🎯 ১০০০০% সফল! 'number' কলামটি উইথড্র টেবিলে সফলভাবে যুক্ত হয়ে গেছে।\n";
    echo "এখন আপনার উইথড্র পেজটি মূল সাইটের কোডের সাথে সম্পূর্ণ সামঞ্জস্যপূর্ণ।";
} else {
    // যদি অলরেডি কলামটি থাকে বা অন্য কোনো কুয়েরি রেসপন্স আসে
    echo "📢 তথ্য: " . $conn->error;
}
?>
