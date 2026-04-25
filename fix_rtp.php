<?php
include 'db.php';

// ১. কলামটি আছে কি না চেক করে না থাকলে তৈরি করা
$check = $conn->query("SHOW COLUMNS FROM `settings` LIKE 'rtp_value'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE `settings` ADD `rtp_value` INT DEFAULT 50");
}

// ২. ডিফল্ট মান আপডেট করা
$conn->query("UPDATE `settings` SET `rtp_value` = 50 WHERE id = 1");

echo "<h1>✅ RTP System Fixed!</h1>";
echo "<p>এখন আপনি Ludu গেমটি ওপেন করতে পারবেন।</p>";
?>
