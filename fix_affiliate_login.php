<?php
include 'db.php';
// ১. আপনার অ্যাকাউন্টের পাসওয়ার্ড রিসেট এবং রোল চেক
$u = "আপনার_অ্যাফিলিয়েট_ইউজারনেম"; // এখানে আপনার আসল ইউজারনেম দিন
$p = "123456"; // নতুন একটি সহজ পাসওয়ার্ড দিন

$sql = "UPDATE users SET password = '$p', is_affiliate = 1 WHERE username = '$u'";

if($conn->query($sql)) {
    echo "<h1>✅ আপনার অ্যাকাউন্ট এখন লগইনের জন্য রেডি!</h1>";
    echo "<p>ইউজারনেম: $u | পাসওয়ার্ড: $p</p>";
} else {
    echo "এরর: " . $conn->error;
}
?>
