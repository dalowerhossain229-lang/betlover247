<?php
include 'db.php';

$user = 'admin';
$pass = '123456'; // আপনার পছন্দমতো পাসওয়ার্ড দিন

// পাসওয়ার্ডটি হ্যাশ করে সেভ করা নিরাপদ
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

$sql = "INSERT INTO admin_users (username, password, role, status) VALUES ('$user', '$hashed_pass', 'admin', 'active')";

if ($conn->query($sql)) {
    echo "<h1>অ্যাডমিন অ্যাকাউন্ট তৈরি সফল!</h1>";
    echo "<p>ইউজার: admin | পাসওয়ার্ড: 123456</p>";
} else {
    echo "এরর: " . $conn->error;
}
?>
