<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. PB ডিপোজিটের জন্য আলাদা টেবিল তৈরি করা
$sql_pb = "CREATE TABLE IF NOT EXISTS pb_deposits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    amount INT,
    method VARCHAR(50),
    trx_id VARCHAR(100),
    status VARCHAR(20) DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_pb)) {
    echo "<h2 style='color:green;'>✅ pb_deposits টেবিল সফলভাবে তৈরি হয়েছে!</h2>";
} else {
    echo "<h2 style='color:red;'>❌ টেবিল তৈরিতে ভুল: " . $conn->error . "</h2>";
}

// ২. ইউজার টেবিলে টার্নওভার কলাম চেক করা (যদি না থাকে তবে যোগ হবে)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
    echo "<p style='color:blue;'>ℹ️ ইউজার টেবিলে টার্নওভার কলামগুলো যোগ করা হয়েছে।</p>";
}

echo "<hr style='width:300px; margin:20px auto;'>";
echo "<p><a href='pb_deposit.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন PB ডিপোজিট পেজে যান</a></p>";
echo "</div>";
?>
