<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// অ্যাফিলিয়েট সিস্টেমের কলামগুলো আছে কি না চেক করে যোগ করা
$cols = [
    'aff_instant_earned' => "DECIMAL(10,2) DEFAULT 0.00",
    'aff_monthly_ngr' => "DECIMAL(10,2) DEFAULT 0.00"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
    }
}

echo "<h1 style='color:green;'>✅ অ্যাফিলিয়েট সিস্টেম ডাটাবেসে সফলভাবে আপডেট হয়েছে!</h1>";
echo "<p><a href='admin_panel.php' style='background:#ffdf1b; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন এডমিন প্যানেলে যান</a></p>";
echo "</div>";
?>
