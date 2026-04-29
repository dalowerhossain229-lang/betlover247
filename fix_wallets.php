<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Wallet & Turnover Column Fixer</h2>";

// ১. ব্যালেন্স এবং টার্নওভার কলামগুলো নিখুঁতভাবে তৈরি বা আপডেট করার কুয়েরি
$sql = "ALTER TABLE users 
        MODIFY COLUMN balance DECIMAL(10,2) DEFAULT 0.00,
        MODIFY COLUMN pb_balance DECIMAL(10,2) DEFAULT 0.00,
        MODIFY COLUMN bonus_balance DECIMAL(10,2) DEFAULT 0.00,
        MODIFY COLUMN main_t DECIMAL(10,2) DEFAULT 0.00,
        MODIFY COLUMN pb_t DECIMAL(10,2) DEFAULT 0.00,
        MODIFY COLUMN bonus_t DECIMAL(10,2) DEFAULT 0.00";

if ($conn->query($sql)) {
    echo "<p style='color:#00ff88;'>✅ Success! মেইন, পিবি এবং বোনাস ওয়ালেটের সব কলাম ঠিক করা হয়েছে।</p>";
    
    // ২. টেস্ট করার জন্য আপনার আইডিতে কিছু পিবি ব্যালেন্স যোগ করা (ঐচ্ছিক)
    // আপনার ইউজারনেম 'Abid123' হলে এটি কাজ করবে
    $conn->query("UPDATE users SET pb_balance = 500.00, bonus_balance = 200.00 WHERE username = 'Abid123'");
    
    echo "<p>এখন আপনার PB এবং Bonus ব্যালেন্স থেকে বাজি ধরলে সেটি সরাসরি আপডেট হবে।</p>";
    echo "<br><a href='play.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>গেম পেজে ফিরে যান</a>";
} else {
    echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
}

echo "</body>";
?>
