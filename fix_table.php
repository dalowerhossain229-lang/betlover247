<?php
include 'db.php';
// PB ডিপোজিটের জন্য সম্পূর্ণ আলাদা টেবিল
$sql = "CREATE TABLE IF NOT EXISTS pb_deposits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    amount INT,
    method VARCHAR(50),
    trx_id VARCHAR(100),
    status VARCHAR(20) DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql)) {
    echo "<h1 style='color:green; text-align:center;'>✅ PB সিস্টেম ডাটাবেসে রেডি!</h1>";
}
?>
