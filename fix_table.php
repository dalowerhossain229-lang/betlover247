<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";

// ৩টি আলাদা টার্নওভারের ঘরগুলো আছে কি না চেক করে যোগ করা
$cols = [
    'bonus_t_target' => "INT DEFAULT 0",
    'bonus_t_done' => "INT DEFAULT 0",
    'pb_t_target' => "INT DEFAULT 0",
    'pb_t_done' => "INT DEFAULT 0"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
    }
}

echo "<h2 style='color:green;'>✅ ৩টি টার্নওভার সিস্টেম ডাটাবেসে সফলভাবে আপডেট হয়েছে!</h2>";
echo "<p><a href='profile.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>এখন প্রোফাইল পেজে যান</a></p>";
echo "</div>";
?>
