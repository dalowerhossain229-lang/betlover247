<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// বোনাস সিস্টেমের জন্য কলামগুলো আছে কি না চেক করে যোগ করা
$cols = [
    'bonus_balance' => "DECIMAL(10,2) DEFAULT 0.00",
    'bonus_t_target' => "INT DEFAULT 0",
    'bonus_t_done' => "INT DEFAULT 0"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
    }
}

echo "<h1 style='color:green;'>✅ বোনাস সিস্টেম ডাটাবেসে সফলভাবে আপডেট হয়েছে!</h1>";
echo "<p><a href='admin_bonus.php' style='background:#ffdf1b; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন বোনাস পেজে যান</a></p>";
echo "</div>";
?>
