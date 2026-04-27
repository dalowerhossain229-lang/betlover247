<?php
include 'db.php';

echo "<h2>⚙️ Syncing Bonus Target from Admin Settings...</h2>";

// ১. অ্যাডমিন প্যানেলের সেটিংস টেবিল থেকে বর্তমান মানটি সরাসরি পড়া
$st = $conn->query("SELECT bonus_target FROM settings WHERE id = 1")->fetch_assoc();
$admin_choice = (float)($st['bonus_target'] ?? 0);

// ২. ডাটাবেসকে কমান্ড দেওয়া যেন এই অ্যাডমিন মানটিকেই ফাইনাল টার্গেট ধরা হয়
$conn->query("UPDATE settings SET bonus_target = $admin_choice WHERE id = 1");

echo "✅ <b>Success!</b> অ্যাডমিন প্যানেলে আপনি বর্তমানে ৳ <b>$admin_choice</b> সেট করে রেখেছেন এবং প্রোফাইল এখন এটিই দেখাবে।<br>";
echo "<h3>🚀 এখন প্রোফাইল রিফ্রেশ দিন।</h3>";
?>
