<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>📋 Database Table & Column Inspector</h2>";

// ১. ডাটাবেসের সব টেবিলের নাম দেখা
$tables = $conn->query("SHOW TABLES");

if ($tables) {
    while ($row = $tables->fetch_array()) {
        $tableName = $row[0];
        echo "<div style='background:#111; border:1px solid #333; padding:15px; margin-bottom:20px; border-radius:10px;'>";
        echo "<h3 style='color:#00ff88; margin-top:0;'>📦 Table: $tableName</h3>";
        
        // ২. প্রতিটি টেবিলের কলামের নাম দেখা
        $columns = $conn->query("SHOW COLUMNS FROM $tableName");
        echo "<ul style='color:#ccc; font-size:13px;'>";
        while ($col = $columns->fetch_assoc()) {
            echo "<li><b>" . $col['Field'] . "</b> (" . $col['Type'] . ")</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
} else {
    echo "<p style='color:red;'>কোনো টেবিল পাওয়া যায়নি। কানেকশন চেক করুন।</p>";
}

echo "</body>";
?>
