<?php
include 'db.php';
// ডাটাবেস থেকে সব পুরনো এবং ভুল রেকর্ড মুছে ফেলা
$conn->query("TRUNCATE TABLE slider_images");
echo "<h1>✅ স্লাইডার ডাটাবেস ক্লিন হয়েছে!</h1>";
echo "<p>এখন আপনি নতুন করে ছবি আপলোড করতে পারবেন।</p>";
?>
