<?php
session_start();
include 'db.php';
$u = $_SESSION['user_id'];
$type = $_GET['type'];

// ডাটাবেসে ইউজারের একটিভ ওয়ালেট আপডেট করা
$conn->query("UPDATE users SET active_wallet = '$type' WHERE username = '$u'");
?>
