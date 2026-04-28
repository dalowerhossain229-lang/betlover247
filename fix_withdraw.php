<?php
include 'db.php';
session_start();

// ১. আপনার লগইন করা ইউজারনেমটি এখানে বসান (টেস্ট করার জন্য)
$u = $_SESSION['username'] ?? 'আপনার_ইউজারনেম'; 

// ২. ডাটাবেসে কলাম চেক ও তৈরি করা (যদি না থাকে)
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS main_t DECIMAL(10,2) DEFAULT 0.00");
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS t_main DECIMAL(10,2) DEFAULT 1000.00");

// ৩. আপনার আইডিতে ২১১৮৯ টার্নওভার সেট করে দেওয়া (যাতে লাল বক্স চলে যায়)
$update = $conn->query("UPDATE users SET main_t = 21189, t_main = 1000 WHERE username = '$u'");

if($update) {
    echo "<h2 style='color:green;'>✅ Fix Successful!</h2>";
    echo "<p>আপনার টার্নওভার ২১,১৮৯ সেট করা হয়েছে। এখন উইথড্র পেজ ওপেন হবে।</p>";
    echo "<a href='withdraw.php'>উইথড্র পেজে যান</a>";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
