<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. deposits টেবিলে 'date' কলাম যোগ করা
$check_dep = $conn->query("SHOW COLUMNS FROM deposits LIKE 'date'");
if ($check_dep->num_rows == 0) {
    $conn->query("ALTER TABLE deposits ADD date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "<h2 style='color:green;'>✅ Deposits টেবিলে তারিখ সিস্টেম যোগ হয়েছে!</h2>";
}

// ২. withdraws টেবিলে 'date' কলাম যোগ করা
$check_wd = $conn->query("SHOW COLUMNS FROM withdraws LIKE 'date'");
if ($check_wd->num_rows == 0) {
    $conn->query("ALTER TABLE withdraws ADD date TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "<h2 style='color:green;'>✅ Withdraws টেবিলে তারিখ সিস্টেম যোগ হয়েছে!</h2>";
}

echo "<hr style='width:300px; margin:20px auto;'>";
echo "<p><a href='history.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন ট্রানজেকশন হিস্ট্রি দেখুন</a></p>";
echo "</div>";
?>
