<?php
// এখানে সরাসরি পাসওয়ার্ড না লিখে রেন্ডার বা ভার্সেলের Environment Variables ব্যবহার করা হয়েছে
$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') ?: "";
$db   = getenv('DB_NAME') ?: "test_db";

// কানেকশন তৈরি
$conn = new mysqli($host, $user, $pass, $db);

// কানেকশন চেক
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
