<?php
include 'db.php';
// ডাটাবেসের সব টার্গেট একদম জিরো করে দেওয়া
$conn->query("UPDATE settings SET bonus_target = 0, pb_target = 0 WHERE id = 1");
echo "✅ এখন আপনার সব টার্গেট ০ হয়ে গেছে! প্রোফাইল চেক করুন।";
?>
