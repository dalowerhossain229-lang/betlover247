<?php
include 'db.php';

// ১. কলামটি আছে কি না চেক করে না থাকলে তৈরি করা
$check = $conn->query("SHOW COLUMNS FROM `settings` LIKE 'game_logic'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE `settings` ADD `game_logic` VARCHAR(20) DEFAULT 'random'");
}

// ২. ডিফল্ট মান আপডেট করা
$conn->query("UPDATE `settings` SET `game_logic` = 'random' WHERE id = 1");

echo "<h1>✅ Game Logic System Installed!</h1>";
echo "<p>এখন আপনি ডাটাবেস থেকে গেমের হার-জিত নিয়ন্ত্রণ করতে পারবেন।</p>";
?>
