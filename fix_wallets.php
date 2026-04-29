<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Wallet & Turnover Column Fixer (Final)</h2>";

// কলামগুলো তৈরি বা আপডেট করার কুয়েরি (ADD কলাম ব্যবহার করা হয়েছে)
$queries = [
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS pb_balance DECIMAL(10,2) DEFAULT 0.00",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS bonus_balance DECIMAL(10,2) DEFAULT 0.00",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS main_t DECIMAL(10,2) DEFAULT 0.00",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS pb_t DECIMAL(10,2) DEFAULT 0.00",
    "ALTER TABLE users ADD COLUMN IF NOT EXISTS bonus_t DECIMAL(10,2) DEFAULT 0.00",
    "ALTER TABLE users MODIFY COLUMN balance DECIMAL(10,2) DEFAULT 0.00"
];

$success = true;
foreach ($queries as $sql) {
    if (!$conn->query($sql)) {
        echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
        $success = false;
    }
}

if ($success) {
    echo "<p style='color:#00ff88;'>✅ Success! মেইন, পিবি এবং বোনাস ওয়ালেটের সব কলাম (pb_t, main_t ইত্যাদি) এখন ডাটাবেসে তৈরি হয়ে গেছে।</p>";
    
    // টেস্ট ব্যালেন্স এড করা (Abid123 এর জন্য)
    $conn->query("UPDATE users SET pb_balance = 500.00, bonus_balance = 200.00 WHERE username = 'Abid123'");
    
    echo "<br><a href='play.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>এখন গেম পেজে গিয়ে চেক করুন</a>";
}

echo "</body>";
?>
