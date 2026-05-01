<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Database Wallet Fixer</h2>";

// ১. চেক করা কলামটি আছে কি না
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'active_wallet'");

if ($check->num_rows == 0) {
    // ২. কলামটি না থাকলে তৈরি করা
    $sql = "ALTER TABLE users ADD COLUMN active_wallet VARCHAR(20) DEFAULT 'main'";
    if ($conn->query($sql)) {
        echo "<p style='color:#00ff88;'>✅ 'active_wallet' কলাম সফলভাবে তৈরি করা হয়েছে।</p>";
    } else {
        echo "<p style='color:red;'>❌ এরর: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:#ffdf1b;'>ℹ️ কলামটি আগে থেকেই ডাটাবেসে আছে।</p>";
}

echo "<h3 style='color:#00ff88;'>🚀 এখন আপনার গেমের ব্যালেন্স সুইচ সিস্টেম পুরোপুরি কাজ করবে!</h3>";
echo "</body>";
?>
