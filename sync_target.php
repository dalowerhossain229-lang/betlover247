<?php
session_start();
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🔄 Dynamic Target Sync Tool</h2>";

// ১. সেশন থেকে বর্তমান ইউজারকে ধরা
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';

if (empty($u)) {
    echo "<p style='color:red;'>❌ আগে লগইন করুন!</p>";
    exit();
}

// ২. ডাইনামিক লজিক: ইউজারের টোটাল ডিপোজিট অনুযায়ী টার্গেট সেট করা
// আমরা ধরে নিচ্ছি আপনার ডিপোজিট কলামের নাম 'total_deposit' 
// (যদি কলামের নাম অন্য কিছু হয়, তবে এখানে তা বদলে দিন)

$sql = "UPDATE users SET t_main = (SELECT SUM(amount) FROM deposits WHERE username = '$u' AND status = 'Approved') WHERE username = '$u'";

// যদি আপনার আলাদা ডিপোজিট টেবিল না থাকে, তবে নিচের সহজ কুয়েরিটি কাজ করবে:
// $sql = "UPDATE users SET t_main = balance WHERE username = '$u'"; // একটি উদাহরণ

if ($conn->query($sql)) {
    echo "<p style='color:#00ff88;'>✅ অভিনন্দন ভাইজান! আপনার টার্গেট এখন থেকে আপনার আসল ডিপোজিটের সাথে ডাইনামিক হয়ে গেছে।</p>";
    echo "<p>এখন আর কোনো ফিক্সড ১০০০ বা ৭০০ দেখা যাবে না।</p>";
    echo "<br><a href='withdraw.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>উইথড্র পেজে গিয়ে ম্যাজিক দেখুন</a>";
} else {
    echo "❌ Error: " . $conn->error;
}

echo "</body>";
?>
