<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px; background:#f4f4f4; padding:20px; border-radius:15px; max-width:500px; margin-left:auto; margin-right:auto; border:1px solid #ccc;'>";

// ১. ডাটাবেসের সব মিসিং কলাম অটো-ফিক্স করা
$cols = [
    'is_affiliate' => "TINYINT(1) DEFAULT 0",
    'ref_code' => "VARCHAR(20) UNIQUE DEFAULT NULL",
    'role' => "VARCHAR(20) DEFAULT 'user'"
];

foreach ($cols as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
        echo "<p style='color:blue;'>✅ কলাম তৈরি হয়েছে: $col</p>";
    }
}

// ২. আপনার একাউন্ট রিসেট করা (ইউজারনেম এবং পাসওয়ার্ড)
$my_user = "dalower"; // এখানে আপনার সঠিক ইউজারনেম দিন
$my_pass = "123456";  // আপনার নতুন সহজ পাসওয়ার্ড

// আপডেট কুয়েরি: এটি আপনার পাসওয়ার্ড এবং এফিলিয়েট পারমিশন একসাথে ঠিক করবে
$update = $conn->query("UPDATE users SET 
    password = '$my_pass', 
    is_affiliate = 1, 
    role = 'user' 
    WHERE username = '$my_user'");

if ($update && $conn->affected_rows > 0) {
    echo "<h1 style='color:green;'>🎉 ১০০০% সফল!</h1>";
    echo "<div style='background:#fff; padding:15px; border-radius:10px; border:1px solid #00ff88; margin-top:20px;'>";
    echo "<p>ইউজারনেম: <b style='color:#000;'>$my_user</b></p>";
    echo "<p>পাসওয়ার্ড: <b style='color:#000;'>$my_pass</b></p>";
    echo "<p style='color:red; font-size:12px;'>*টাইপ করার সময় কোনো স্পেস দিবেন না।</p>";
    echo "</div>";
    echo "<p style='margin-top:20px;'><a href='index.php' style='background:#00ff88; color:#000; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold;'>এখন লগইন করুন</a></p>";
} else {
    echo "<h1 style='color:red;'>❌ ইউজার পাওয়া যায়নি!</h1>";
    echo "<p>আপনার ইউজারনেম <b>$my_user</b> ডাটাবেসে নেই। দয়া করে সঠিক ইউজারনেমটি কোডে লিখে আবার রান করুন।</p>";
}

echo "</div>";
?>
