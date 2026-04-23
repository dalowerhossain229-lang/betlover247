<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. বাজি (Bets) রেকর্ড রাখার জন্য নতুন টেবিল তৈরি
$sql_bets = "CREATE TABLE IF NOT EXISTS bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    amount INT,
    game_id VARCHAR(50),
    status VARCHAR(20) DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_bets)) {
    echo "<h2 style='color:green;'>✅ bets টেবিল সফলভাবে তৈরি হয়েছে!</h2>";
} else {
    echo "<h2 style='color:red;'>❌ bets টেবিল তৈরিতে এরর: " . $conn->error . "</h2>";
}

// ২. টার্নওভার কলামগুলো চেক করে যোগ করা (নিরাপদ পদ্ধতি)
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
echo "<p style='color:blue; font-weight:bold;'>সব সিস্টেম ডাটাবেসে এখন আপডেট আছে।</p>";
echo "<p><a href='profile.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>প্রোফাইল চেক করুন</a></p>";
echo "</div>";
?>
