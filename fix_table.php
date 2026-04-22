<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. চেক করা কলামগুলো আছে কি না
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_target'");

if ($check->num_rows > 0) {
    echo "<h2 style='color:blue;'>ℹ️ টার্নওভার সিস্টেম ডাটাবেসে আগেই সেট করা আছে।</h2>";
} else {
    // যদি না থাকে তবেই যোগ করবে
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
    echo "<h2 style='color:green;'>✅ টার্নওভার সিস্টেম সফলভাবে যোগ হয়েছে!</h2>";
}

echo "<hr style='width:300px; margin:20px auto;'>";
echo "<p><a href='withdraw.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন উইথড্র পেজে যান</a></p>";
echo "</div>";
?>

