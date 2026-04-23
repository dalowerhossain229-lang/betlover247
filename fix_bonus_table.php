<?php
include 'db.php';

// বোনাস ব্যালেন্স এবং বোনাস টার্নওভারের ঘরগুলো তৈরি করা
$sql = "ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS bonus_balance DECIMAL(10,2) DEFAULT 0.00,
        ADD COLUMN IF NOT EXISTS bonus_t_target INT DEFAULT 0,
        ADD COLUMN IF NOT EXISTS bonus_t_done INT DEFAULT 0";

if ($conn->query($sql)) {
    echo "<h1 style='color:green; text-align:center; font-family:sans-serif;'>✅ বোনাস সিস্টেম ডাটাবেসে রেডি!</h1>";
} else {
    echo "এরর: " . $conn->error;
}
?>
