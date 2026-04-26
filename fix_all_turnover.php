<?php
include 'db.php';
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS bonus_turnover DECIMAL(10, 2) DEFAULT 0.00");
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS pb_turnover DECIMAL(10, 2) DEFAULT 0.00");
echo "<h1>✅ All Turnover Columns Added!</h1>";
?>
