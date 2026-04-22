<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. ইউজার টেবিলে টার্নওভার কলামগুলো যোগ করা
$check_t = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check_t->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
    echo "<h2 style='color:green;'>✅ টার্নওভার সিস্টেম ডাটাবেসে যোগ হয়েছে!</h2>";
}

// ২. উইথড্র টেবিলে মেথড এবং তারিখ কলাম যোগ করা (যদি না থাকে)
$conn->query("ALTER TABLE withdraws ADD COLUMN IF NOT EXISTS method VARCHAR(100) DEFAULT NULL");
$conn->query("ALTER TABLE withdraws ADD COLUMN IF NOT EXISTS date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");

echo "<hr style='width:300px; margin:20px auto;'>";
echo "<p><a href='withdraw.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন উইথড্র পেজে যান</a></p>";
echo "</div>";
?>
