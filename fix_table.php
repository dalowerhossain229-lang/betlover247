<?php
include 'db.php';

// users টেবিলে ৩টি নম্বর সেভ করার কলাম যোগ করা
$sql = "ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS p_bkash VARCHAR(15) DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS p_nagad VARCHAR(15) DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS p_rocket VARCHAR(15) DEFAULT NULL";

if ($conn->query($sql)) {
    echo "<h1 style='color:green; text-align:center;'>✅ পেমেন্ট নম্বর কলাম সফলভাবে যোগ হয়েছে!</h1>";
    echo "<p style='text-align:center;'><a href='profile.php'>এখন প্রোফাইল পেজে যান</a></p>";
} else {
    echo "এরর: " . $conn->error;
}
?>
