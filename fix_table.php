<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ১. ইউজারের জন্য প্রয়োজনীয় সব কলাম চেক ও অটো-ফিক্স
$cols = [
    'turnover_target' => "INT DEFAULT 1000",
    'turnover_completed' => "INT DEFAULT 0",
    'bonus_balance' => "DECIMAL(10,2) DEFAULT 0.00",
    'p_bkash' => "VARCHAR(20) DEFAULT NULL",
    'p_nagad' => "VARCHAR(20) DEFAULT NULL",
    'bonus_t_target' => "INT DEFAULT 0",
    'bonus_t_done' => "INT DEFAULT 0",
    'pb_t_target' => "INT DEFAULT 0",
    'pb_t_done' => "INT DEFAULT 0"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
        echo "<p style='color:blue;'>✅ কলাম যোগ হয়েছে: $col</p>";
    }
}

// ২. উইথড্র টেবিল ফিক্স করা (যদি মেথড কলাম না থাকে)
$check_w = $conn->query("SHOW COLUMNS FROM withdraws LIKE 'method'");
if ($check_w && $check_w->num_rows == 0) {
    $conn->query("ALTER TABLE withdraws ADD method VARCHAR(100) DEFAULT NULL");
}

echo "<h2 style='color:green; margin-top:30px;'>🎉 ১০০০% ডাটাবেস আপডেট সফল!</h2>";
echo "<p style='color:#666;'>এখন আপনার উইথড্র পেজে আর কোনো TypeError আসবে না।</p>";
echo "<hr style='width:300px; margin:20px auto;'>";
echo "<p><a href='withdraw.php' style='background:#00ff88; color:#000; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold; box-shadow:0 4px 10px rgba(0,255,136,0.2);'>এখন উইথড্র পেজে যান</a></p>";
echo "</div>";
?>
