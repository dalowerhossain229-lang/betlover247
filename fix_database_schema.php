<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Database Schema Fixer</h2>";

// ১. t_main কলামের ডিফল্ট মান ১০০০ থেকে কমিয়ে ০.০০ করা
$sql = "ALTER TABLE users MODIFY COLUMN t_main DECIMAL(10,2) DEFAULT 0.00";

if ($conn->query($sql)) {
    echo "<p style='color:#00ff88;'>✅ Success! 't_main' কলামের ডিফল্ট মান এখন থেকে ০.০০ সেট করা হয়েছে।</p>";
    echo "<p>এখন থেকে নতুন ইউজারদের আর অটোমেটিক ১০০০ টার্গেট আসবে না।</p>";
    
    // ২. আপনার বর্তমান আইডির (BETLOVER777) ১০০০ মুছে ২৫০ করার কমান্ড
    $u = "BETLOVER777"; 
    $conn->query("UPDATE users SET t_main = 250.00 WHERE username = '$u'");
    
    echo "<p style='color:#ffdf1b;'>🎯 আপনার আইডির (BETLOVER777) টার্গেটও ২৫০ করে দেওয়া হয়েছে।</p>";
    echo "<br><a href='withdraw.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>এখন উইথড্র পেজে গিয়ে ম্যাজিক দেখুন</a>";
} else {
    echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
}

echo "</body>";
?>
