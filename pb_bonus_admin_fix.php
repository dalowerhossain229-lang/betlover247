<?php
include 'db.php';

echo "<h2>⚙️ Syncing PB & Bonus Targets from Admin Settings...</h2>";

// ১. অ্যাডমিন প্যানেলের সেটিংস টেবিল থেকে বর্তমান মানগুলো সরাসরি পড়া
$st = $conn->query("SELECT bonus_target, pb_target FROM settings WHERE id = 1")->fetch_assoc();

// অ্যাডমিন প্যানেলে যা আছে সেটিই নেওয়া হবে, কোনো ডিফল্ট ৫০০০ বা ১০০০০ নেই
$admin_bonus = (float)($st['bonus_target'] ?? 0);
$admin_pb = (float)($st['pb_target'] ?? 0);

// ২. ডাটাবেসকে কমান্ড দেওয়া যেন এই অ্যাডমিন মানগুলোকেই ফাইনাল টার্গেট ধরা হয়
$conn->query("UPDATE settings SET 
    bonus_target = $admin_bonus, 
    pb_target = $admin_pb 
    WHERE id = 1");

echo "✅ <b>Success!</b> ডাটাবেস এখন আপনার অ্যাডমিন প্যানেলের সাথে ১০০% সিঙ্কড।<br>";
echo "💰 Admin Bonus Set: ৳ <b>$admin_bonus</b><br>";
echo "💎 Admin PB Set: ৳ <b>$admin_pb</b><br>";
echo "<h3>🚀 এখন প্রোফাইল রিফ্রেশ দিন।</h3>";
?>
