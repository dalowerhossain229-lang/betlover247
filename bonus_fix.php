<?php
include 'db.php'; // আপনার ডাটাবেস কানেকশন ফাইল

// ডাটাবেসে কলামটি আছে কিনা তা চেক করার সহজ পদ্ধতি
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'bonus_target'");

if ($check->num_rows == 0) {
    // যদি কলাম না থাকে তবেই তৈরি করবে
    $sql = "ALTER TABLE users ADD bonus_target DECIMAL(10,2) DEFAULT 0.00";
    if ($conn->query($sql) === TRUE) {
        echo "<h2>✅ Success! bonus_target কলাম তৈরি হয়েছে।</h2>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
} else {
    echo "<h2>ℹ️ কলামটি অলরেডি ডাটাবেসে আছে।</h2>";
}

echo "<br><a href='index.php'>সাইটে ফিরে যান</a>";
?>
