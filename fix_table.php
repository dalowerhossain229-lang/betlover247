<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. পেমেন্ট নম্বর কলামগুলো আছে কি না চেক করা
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'p_bkash'");

if ($check->num_rows == 0) {
    // যদি না থাকে, তবে একটি একটি করে যোগ করা
    $conn->query("ALTER TABLE users ADD p_bkash VARCHAR(15) DEFAULT NULL");
    $conn->query("ALTER TABLE users ADD p_nagad VARCHAR(15) DEFAULT NULL");
    $conn->query("ALTER TABLE users ADD p_rocket VARCHAR(15) DEFAULT NULL");
    
    echo "<h2 style='color:green;'>✅ পেমেন্ট নম্বর সিস্টেম ডাটাবেসে সফলভাবে যোগ হয়েছে!</h2>";
} else {
    echo "<h2 style='color:blue;'>ℹ️ সিস্টেমটি ডাটাবেসে আগেই সেট করা আছে।</h2>";
}

echo "<p><a href='profile.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>প্রোফাইল পেজে ফিরে যান</a></p>";
echo "</div>";
?>
