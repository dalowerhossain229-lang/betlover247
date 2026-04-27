<?php
include 'db.php';

echo "<h2>🛠️ PB & Target System Repairing...</h2>";

// ১. ইউজার টেবিলে PB ব্যালেন্স ও টার্নওভার কলাম চেক ও তৈরি
$user_cols = [
    'pb_balance' => "DECIMAL(10, 2) DEFAULT 0.00",
    'pb_turnover' => "DECIMAL(10, 2) DEFAULT 0.00"
];

foreach ($user_cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM `users` LIKE '$col'");
    if ($check->num_rows == 0) {
        $conn->query("ALTER TABLE `users` ADD `$col` $type");
        echo "✅ Column '$col' added to users table.<br>";
    }
}

// ২. সেটিংস টেবিলে অ্যাডমিন টার্গেট কলাম চেক ও তৈরি
$settings_cols = [
    'main_target' => "INT DEFAULT 1000",
    'bonus_target' => "INT DEFAULT 5000",
    'pb_target' => "INT DEFAULT 10000"
];

foreach ($settings_cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM `settings` LIKE '$col'");
    if ($check->num_rows == 0) {
        $conn->query("ALTER TABLE `settings` ADD `$col` $type");
        echo "✅ Target column '$col' added to settings table.<br>";
    }
}

// ৩. ডিফল্ট টার্গেট ডাটা সেট করা (যদি খালি থাকে)
$conn->query("UPDATE settings SET main_target = 1000, bonus_target = 5000, pb_target = 10000 WHERE id = 1");

echo "<h3>🚀 Success! Everything is Syncing Now.</h3>";
echo "<p>এখন আপনার প্রোফাইল পেজে যান, পিবি ব্যালেন্স এখন মেইন থেকে আলাদা হয়ে পিবি বক্সেই দেখাবে।</p>";
?>
