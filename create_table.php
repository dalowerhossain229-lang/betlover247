<?php
// create_table.php - ডাটাবেজে মিসিং 'date' কলাম যুক্ত করার স্ক্রিপ্ট
include 'db.php';

header('Content-Type: text/plain; charset=utf-8');

// টেবিলের ভেতর 'date' কলামটি কারেন্ট টাইমস্ট্যাম্প সহ যুক্ত করার এসকিউএল কুয়েরি
$sql = "ALTER TABLE `withdraw_requests` ADD COLUMN `date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `status`;";

if ($conn->query($sql) === TRUE) {
    echo "🎯 ১০০০০% সফল! 'date' কলামটি উইথড্র টেবিলে সফলভাবে যুক্ত হয়ে গেছে।\n";
    echo "এখন আপনার উইথড্র পেজটি মূল সাইটের কোডের সাথে সম্পূর্ণ সামঞ্জস্যপূর্ণ।";
} else {
    echo "📢 তথ্য: " . $conn->error;
}
?>
