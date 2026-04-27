<?php
include 'db.php';
// এখানে আপনি যা সেট করবেন, প্রোফাইলে হুবহু তাই দেখাবে
$conn->query("UPDATE settings SET bonus_target = 5000, pb_target = 10000 WHERE id = 1");
echo "<h1>✅ Database Targets Updated!</h1>";
?>
