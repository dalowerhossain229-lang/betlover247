<?php
include 'db.php';

// শুধুমাত্র হারিয়ে যাওয়া site_configs টেবিলটি তৈরির কুয়েরি
$sql = "CREATE TABLE IF NOT EXISTS site_configs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(100) UNIQUE,
    config_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql)) {
    echo "<h1 style='color:green; text-align:center;'>✅ site_configs টেবিলটি সফলভাবে পুনরায় তৈরি হয়েছে!</h1>";
    echo "<p style='text-align:center;'>এখন আপনার প্রোমো এবং সাইট কন্ট্রোল পেজগুলো আবার কাজ করবে।</p>";
    echo "<div style='text-align:center;'><a href='admin_panel.php'>অ্যাডমিন প্যানেলে ফিরে যান</a></div>";
} else {
    echo "এরর: " . $conn->error;
}
?>
