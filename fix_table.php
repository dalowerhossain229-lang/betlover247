<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ৩টি টার্নওভার লাইনের জন্য ডাটাবেস আপডেট
$sql = "ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS bonus_t_target INT DEFAULT 0,
        ADD COLUMN IF NOT EXISTS bonus_t_done INT DEFAULT 0,
        ADD COLUMN IF NOT EXISTS pb_t_target INT DEFAULT 0,
        ADD COLUMN IF NOT EXISTS pb_t_done INT DEFAULT 0";

if ($conn->query($sql)) {
    echo "<h2 style='color:green;'>✅ ৩টি টার্নওভার কলাম সফলভাবে যোগ হয়েছে!</h2>";
    echo "<p><a href='profile.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন প্রোফাইল পেজে যান</a></p>";
} else {
    echo "<h2 style='color:red;'>❌ এরর: " . $conn->error . "</h2>";
}

echo "</div>";
?>
