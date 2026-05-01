<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ WhatsApp Column Fixer</h2>";

// ১. চেক করা কলামটি আছে কি না, না থাকলে তৈরি করা
$check = $conn->query("SHOW COLUMNS FROM settings LIKE 'whatsapp_no'");

if ($check->num_rows == 0) {
    // কলাম তৈরি করা
    $conn->query("ALTER TABLE settings ADD COLUMN whatsapp_no VARCHAR(20) DEFAULT '8801700000000'");
    echo "<p style='color:#00ff88;'>✅ 'whatsapp_no' কলাম সফলভাবে তৈরি করা হয়েছে।</p>";
} else {
    echo "<p style='color:#ffdf1b;'>ℹ️ কলামটি আগে থেকেই আছে।</p>";
}

// ২. একটি ডিফল্ট নম্বর সেট করে দেওয়া (যাতে বাটন খালি না থাকে)
$conn->query("UPDATE settings SET whatsapp_no = '8801700000000' WHERE id = 1");

echo "<h3 style='color:#00ff88;'>🚀 এখন আপনি এডমিন প্যানেল থেকে নম্বরটি পরিবর্তন করতে পারবেন!</h3>";
echo "</body>";
?>
