<?php
// আপনার ডাটাবেস কানেকশন ফাইল
include 'db.php'; 

// ১. ইউজার টেবিলে কলামটি তৈরি করা (যদি না থাকে)
$sql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS bonus_target DECIMAL(10,2) DEFAULT 0.00";

if ($conn->query($sql) === TRUE) {
    echo "<h2>✅ Database Table Fixed!</h2>";
    echo "<p>এখন আপনি অ্যাডমিন প্যানেল থেকে যেকোনো অ্যামাউন্ট সেট করতে পারবেন।</p>";
} else {
    echo "❌ Error: " . $conn->error;
}

echo "<br><a href='index.php'>সাইটে ফিরে যান</a>";
?>
