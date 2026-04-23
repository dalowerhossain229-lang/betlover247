<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// অ্যাফিলিয়েট সিস্টেমের জন্য প্রয়োজনীয় সব কলাম চেক ও যোগ করা
$cols = [
    'ref_code' => "VARCHAR(20) UNIQUE DEFAULT NULL",
    'ref_by' => "VARCHAR(20) DEFAULT NULL",
    'aff_instant_earned' => "DECIMAL(10,2) DEFAULT 0.00",
    'aff_monthly_ngr' => "DECIMAL(10,2) DEFAULT 0.00"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
    }
}

// প্রতিটি ইউজারের জন্য একটি ইউনিক রেফার কোড তৈরি করা (যদি না থাকে)
$users = $conn->query("SELECT id, username FROM users WHERE ref_code IS NULL OR ref_code = ''");
while($u = $users->fetch_assoc()) {
    $new_code = "BET" . rand(1000, 9999) . $u['id'];
    $conn->query("UPDATE users SET ref_code = '$new_code' WHERE id = " . $u['id']);
}

echo "<h1 style='color:green;'>✅ অ্যাফিলিয়েট ডাটাবেস ১০০০% ফিক্স হয়েছে!</h1>";
echo "<p><a href='affiliate.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন অ্যাফিলিয়েট ড্যাশবোর্ডে যান</a></p>";
echo "</div>";
?>
