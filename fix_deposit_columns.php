<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Deposit Number Database Fixer</h2>";

// যে কলামগুলো চেক করতে হবে
$columns = [
    'admin_bkash_p' => "VARCHAR(20) DEFAULT '017XXXXXXXX'",
    'admin_bkash_a' => "VARCHAR(20) DEFAULT '017XXXXXXXX'",
    'admin_nagad_p' => "VARCHAR(20) DEFAULT '018XXXXXXXX'",
    'admin_nagad_a' => "VARCHAR(20) DEFAULT '018XXXXXXXX'"
];

foreach ($columns as $col => $definition) {
    // কলামটি অলরেডি আছে কি না চেক করা
    $check = $conn->query("SHOW COLUMNS FROM settings LIKE '$col'");
    
    if ($check->num_rows == 0) {
        // যদি না থাকে তবে তৈরি করা
        $sql = "ALTER TABLE settings ADD COLUMN $col $definition";
        if ($conn->query($sql)) {
            echo "<p style='color:#00ff88;'>✅ কলাম তৈরি হয়েছে: <b>$col</b></p>";
        } else {
            echo "<p style='color:red;'>❌ এরর ($col): " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:#ffdf1b;'>ℹ️ কলামটি আগে থেকেই আছে: <b>$col</b></p>";
    }
}

echo "<h3 style='color:#00ff88;'>🚀 ডাটাবেস এখন ৪টি ডিপোজিট নম্বরের জন্য পুরোপুরি রেডি!</h3>";
echo "</body>";
?>
