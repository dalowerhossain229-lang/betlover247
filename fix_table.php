<?php
include 'db.php';

// ১. হারিয়ে যাওয়া sliders টেবিলটি নিখুঁতভাবে তৈরি করা
$sql = "CREATE TABLE IF NOT EXISTS sliders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255) DEFAULT 'slide.png',
    offer_link VARCHAR(255) DEFAULT '#',
    status ENUM('active', 'inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql)) {
    // ২. ১০টি স্লাইডারের জন্য ডিফল্ট রো (Rows) তৈরি করা যাতে লুপে এরর না আসে
    $check = $conn->query("SELECT id FROM sliders");
    if ($check->num_rows == 0) {
        for ($i = 1; $i <= 10; $i++) {
            $conn->query("INSERT INTO sliders (image_url) VALUES ('slide$i.png')");
        }
    }
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
    echo "<h1 style='color:green;'>✅ Sliders টেবিলটি সফলভাবে পুনরায় তৈরি হয়েছে!</h1>";
    echo "<p>এখন আপনার অ্যাডমিন প্যানেলের এররটি চলে যাবে।</p>";
    echo "<a href='manage_site.php' style='background:#00ff88; color:#000; padding:10px 20px; text-decoration:none; border-radius:5px; font-weight:bold;'>কন্ট্রোল পেজে ফিরে যান</a>";
    echo "</div>";
} else {
    echo "এরর হয়েছে: " . $conn->error;
}
?>
