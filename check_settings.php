<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🔍 Settings Table Inspector</h2>";

// ১. সেটিংস টেবিল থেকে সব ডাটা দেখা
$res = $conn->query("SELECT * FROM settings LIMIT 1");

if ($res && $res->num_rows > 0) {
    $settings = $res->fetch_assoc();
    echo "<h3>📊 সেটিংস টেবিলের তথ্য:</h3>";
    echo "<pre style='background:#111; padding:15px; color:#00ff88; border:1px solid #333;'>";
    print_r($settings); // এখানে সব কলামের নাম ও মান দেখা যাবে
    echo "</pre>";

    // ২. যদি কোনো কলামে ১০০০ থাকে, তবে সেটি এখানে ঠিক করার সুযোগ
    echo "<br><br><p style='color:#ffdf1b;'>⚠️ যদি কোনো কলামের নাম 'min_turnover' বা 'default_t' টাইপ হয় এবং সেখানে ১০০০ থাকে, তবে সেটিই আপনার সমস্যার কারণ।</p>";
} else {
    echo "<p style='color:red;'>❌ আপনার ডাটাবেসে 'settings' নামে কোনো টেবিল নেই অথবা সেটি খালি।</p>";
}

echo "</body>";
?>
