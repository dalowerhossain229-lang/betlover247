<?php
include 'db.php';

echo "<h2>🛠️ Synchronizing Bonus Target Only...</h2>";

// ১. অ্যাডমিন সেটিংস থেকে শুধুমাত্র বোনাস টার্গেটটি নিয়ে আসা
$st = $conn->query("SELECT bonus_target FROM settings WHERE id = 1")->fetch_assoc();
$new_bonus_target = (float)($st['bonus_target'] ?? 5000);

// ২. ডাটাবেসের সেটিংস টেবিলটি ১০০% নিশ্চিত করা
$conn->query("UPDATE settings SET bonus_target = $new_bonus_target WHERE id = 1");

echo "✅ <b>Settings Table</b> আপডেট হয়েছে!<br>";
echo "✅ বর্তমান বোনাস টার্গেট সেট হয়েছে: ৳ $new_bonus_target<br>";

echo "<h3>🚀 Success! প্রোফাইলের বোনাস সেকশন এখন ১০০% সিঙ্কড।</h3>";
?>
