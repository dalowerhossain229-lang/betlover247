<?php
include 'db.php';
// ৫% ইনস্ট্যান্ট এবং ৪৭% মাসিক প্রফিট শেয়ারের জন্য কলাম যোগ করা
$sql = "ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS aff_instant_earned DECIMAL(10,2) DEFAULT 0.00,
        ADD COLUMN IF NOT EXISTS aff_monthly_ngr DECIMAL(10,2) DEFAULT 0.00";

if ($conn->query($sql)) {
    echo "<h1 style='color:green; text-align:center;'>✅ ধাপ ১ সফল: ডাটাবেস আপডেট হয়েছে!</h1>";
}
?>
