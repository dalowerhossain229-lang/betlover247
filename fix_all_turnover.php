<?php
include 'db.php';

// ১. bonus_turnover কলাম চেক ও তৈরি
$check1 = $conn->query("SHOW COLUMNS FROM `users` LIKE 'bonus_turnover'");
if ($check1->num_rows == 0) {
    $conn->query("ALTER TABLE `users` ADD `bonus_turnover` DECIMAL(10, 2) DEFAULT 0.00");
}

// ২. pb_turnover কলাম চেক ও তৈরি
$check2 = $conn->query("SHOW COLUMNS FROM `users` LIKE 'pb_turnover'");
if ($check2->num_rows == 0) {
    $conn->query("ALTER TABLE `users` ADD `pb_turnover` DECIMAL(10, 2) DEFAULT 0.00");
}

// ৩. pb_balance কলাম চেক ও তৈরি (যদি না থাকে)
$check3 = $conn->query("SHOW COLUMNS FROM `users` LIKE 'pb_balance'");
if ($check3->num_rows == 0) {
    $conn->query("ALTER TABLE `users` ADD `pb_balance` DECIMAL(10, 2) DEFAULT 0.00");
}

echo "<h1>✅ All Turnover Columns Fixed!</h1>";
echo "<p>এখন আপনার ৩টি ব্যালেন্স সিস্টেম ১০০০% কাজ করবে।</p>";
?>
