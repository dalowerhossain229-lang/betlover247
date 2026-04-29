<?php
session_start();
include 'db.php';

// আপনার ইউজারনেমটি এখানে দিন
$u = $_SESSION['username'] ?? 'Abid1234'; 

// টার্গেট ১০০০ থেকে কমিয়ে ৭০০ করার কমান্ড
$sql = "UPDATE users SET t_main = 700 WHERE username = '$u'";

if ($conn->query($sql)) {
    echo "<h2 style='color:green; text-align:center;'>✅ Success! আপনার টার্গেট ১০০০ থেকে ৭০০ করা হয়েছে।</h2>";
    echo "<p style='text-align:center;'><a href='withdraw.php'>এখন উইথড্র পেজে গিয়ে দেখুন।</a></p>";
} else {
    echo "Error: " . $conn->error;
}
?>
