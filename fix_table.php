<?php
include 'db.php';

// শুধুমাত্র deposits টেবিলটি ফিক্স করার কোড
$check_dep = $conn->query("SHOW COLUMNS FROM deposits LIKE 'method'");
if ($check_dep->num_rows == 0) {
    $conn->query("ALTER TABLE deposits ADD method VARCHAR(50) DEFAULT 'Unknown'");
    echo "<h1 style='color:green; text-align:center;'>✅ ডিপোজিট সিস্টেম ফিক্স হয়েছে!</h1>";
} else {
    echo "<h1 style='color:blue; text-align:center;'>ℹ️ এটি আগেই ফিক্স করা আছে।</h1>";
}
echo "<p style='text-align:center;'><a href='deposit.php'>এখন ডিপোজিট করতে এখানে ক্লিক করুন</a></p>";
?>
