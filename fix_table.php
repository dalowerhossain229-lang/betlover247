<?php
include 'db.php';
// পাসওয়ার্ড কলামের সাইজ বাড়িয়ে ২৫৫ ক্যারেক্টার করা হচ্ছে
$sql = "ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL";
if($conn->query($sql)){
    echo "<h1>✅ ডাটাবেস ১০০০% ফিক্স হয়েছে!</h1>";
    echo "<p>এখন পাসওয়ার্ড পরিবর্তন করে দেখুন, কাজ করবে।</p>";
} else {
    echo "<h1>❌ এরর: " . $conn->error . "</h1>";
}
?>
