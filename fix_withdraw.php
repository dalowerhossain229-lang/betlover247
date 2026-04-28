<?php
include 'db.php';

// ১. ডাটাবেসে থাকা সব ইউজারের লিস্ট দেখা
echo "<h3>আপনার ডাটাবেসে থাকা ইউজারদের লিস্ট:</h3>";
$res = $conn->query("SELECT id, username FROM users LIMIT 10");

if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $u_name = $row['username'];
        // এখান থেকে আপনার সঠিক নামটি কপি করুন
        echo "ID: " . $row['id'] . " - Username: <b>" . $u_name . "</b> <br>";
        
        // ২. সব ইউজারের জন্যই অটোমেটিক টার্নওভার সেট করে দেওয়া (যাতে আপনারটা মিস না হয়)
        $conn->query("UPDATE users SET main_t = 21189, t_main = 1000 WHERE username = '$u_name'");
    }
    echo "<h2 style='color:green;'>✅ কাজ হয়েছে!</h2>";
    echo "<p>লিস্টে থাকা সবার জন্যই টার্নওভার সেট করে দেওয়া হয়েছে।</p>";
    echo "<a href='withdraw.php' style='padding:10px; background:blue; color:white; text-decoration:none;'>এখন উইথড্র পেজে যান</a>";
} else {
    echo "<h2 style='color:red;'>❌ ডাটাবেসে কোনো ইউজার পাওয়া যায়নি!</h2>";
}
?>

