<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Adding Payment Columns</h2>";

// ১. bkash_number এবং nagad_number কলাম যোগ করার কমান্ড
$sql = "ALTER TABLE users 
        ADD COLUMN bkash_number VARCHAR(15) DEFAULT '', 
        ADD COLUMN nagad_number VARCHAR(15) DEFAULT ''";

if ($conn->query($sql)) {
    echo "<p style='color:#00ff88;'>✅ অভিনন্দন! পেমেন্ট কলামগুলো ডাটাবেসে সফলভাবে যোগ করা হয়েছে।</p>";
    echo "<br><a href='withdraw.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>এখন উইথড্র পেজে গিয়ে দেখুন</a>";
} else {
    // যদি আগে থেকেই থাকে তবে এই মেসেজ আসবে
    echo "<p style='color:#ffdf1b;'>⚠️ হয়তো কলামগুলো আগেই আছে অথবা কোনো এরর হয়েছে: " . $conn->error . "</p>";
}

echo "</body>";
?>
