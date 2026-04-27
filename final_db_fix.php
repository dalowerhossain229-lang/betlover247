<?php
include 'db.php';

echo "<h2>⚙️ Database Syncing...</h2>";

// ১. ইউজার টেবিলে ৩টি টার্নওভার এবং ৩টি ব্যালেন্স কলাম নিশ্চিত করা
$cols = [
    'pb_balance' => "DECIMAL(10, 2) DEFAULT 0.00",
    'bonus_balance' => "DECIMAL(10, 2) DEFAULT 0.00",
    'turnover' => "DECIMAL(10, 2) DEFAULT 0.00",
    'bonus_turnover' => "DECIMAL(10, 2) DEFAULT 0.00",
    'pb_turnover' => "DECIMAL(10, 2) DEFAULT 0.00"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM `users` LIKE '$col'");
    if ($check->num_rows == 0) {
        $conn->query("ALTER TABLE `users` ADD `$col` $type");
        echo "✅ Column '$col' created in users table.<br>";
    }
}

// ২. সেটিংস টেবিলে ৩টি অ্যাডমিন টার্গেট কলাম নিশ্চিত করা
$targets = [
    'main_target' => "INT DEFAULT 1000",
    'bonus_target' => "INT DEFAULT 12000",
    'pb_target' => "INT DEFAULT 360000"
];

foreach ($targets as $t => $type) {
    $checkT = $conn->query("SHOW COLUMNS FROM `settings` LIKE '$t'");
    if ($checkT->num_rows == 0) {
        $conn->query("ALTER TABLE `settings` ADD `$t` $type");
        echo "✅ Target '$t' created in settings table.<br>";
    }
}

// ৩. ডিফল্ট টার্গেট মান সেট করা (ID 1 এর জন্য)
$conn->query("UPDATE settings SET main_target = 1000, bonus_target = 12000, pb_target = 360000 WHERE id = 1");

echo "<h3>🚀 Everything is 100% Fixed!</h3>";
echo "<p>এখন আপনার ৩টি ব্যালেন্স, ৩টি টার্নওভার এবং অ্যাডমিন টার্গেট সিস্টেম পুরোপুরি সচল।</p>";
?>
