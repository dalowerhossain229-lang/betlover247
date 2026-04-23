<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. PB ডিপোজিটের জন্য সম্পূর্ণ আলাদা টেবিল তৈরি (এটিই ব্যানার ডাটা জমা রাখবে)
$sql_table = "CREATE TABLE IF NOT EXISTS pb_deposits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    amount INT NOT NULL,
    method VARCHAR(50) NOT NULL,
    trx_id VARCHAR(100) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_table)) {
    echo "<h2 style='color:green;'>✅ pb_deposits টেবিল সফলভাবে তৈরি হয়েছে!</h2>";
} else {
    echo "<h2 style='color:red;'>❌ টেবিল তৈরিতে ভুল: " . $conn->error . "</h2>";
}

// ২. ৩টি আলাদা টার্নওভার ট্র্যাক করার জন্য কলামগুলো চেক করে যোগ করা
$cols = [
    'bonus_t_target' => "INT DEFAULT 0",
    'bonus_t_done' => "INT DEFAULT 0",
    'pb_t_target' => "INT DEFAULT 0",
    'pb_t_done' => "INT DEFAULT 0"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
    }
}

echo "<hr style='width:300px; margin:20px auto;'>";
echo "<p style='color:blue;'>এখন আপনার ডাটাবেসে PB সিস্টেম পুরোপুরি এক্টিভ!</p>";
echo "<p><a href='pb_deposit.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন PB রিকোয়েস্ট পাঠিয়ে দেখুন</a></p>";
echo "</div>";
?>
