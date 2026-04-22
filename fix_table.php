<?php
include 'db.php';

// ১. deposits টেবিলে 'method' কলামটি যোগ করা (যদি না থাকে)
$sql = "ALTER TABLE deposits ADD COLUMN IF NOT EXISTS method VARCHAR(50) DEFAULT 'Unknown';";

if ($conn->query($sql)) {
    echo "<h1 style='color:green; text-align:center;'>✅ ডিপোজিট সিস্টেম ফিক্স হয়েছে!</h1>";
    echo "<p style='text-align:center;'><a href='deposit.php'>এখন এখানে ক্লিক করে ডিপোজিট করুন</a></p>";
} else {
    echo "এরর: " . $conn->error;
}
?>
