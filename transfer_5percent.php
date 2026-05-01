<?php
ob_start();
session_start();
include 'db.php';

// ১. লগইন করা ইউজারকে ধরা
$u = $_SESSION['user_id'] ?? '';
if (empty($u)) { header("Location: index.php"); exit(); }

// ২. ইউজারের aff_balance কত আছে দেখা
$res = $conn->query("SELECT aff_balance FROM users WHERE username = '$u'");
$user = $res->fetch_assoc();
$amount = (float)($user['aff_balance'] ?? 0);

if ($amount > 0) {
    // ৩. aff_balance ০ করে দেওয়া এবং মেইন balance এ যোগ করা
    $update = $conn->query("UPDATE users SET aff_balance = 0, balance = balance + $amount WHERE username = '$u'");
    
    if ($update) {
        echo "<script>alert('৳$amount সফলভাবে মেইন ব্যালেন্সে যোগ হয়েছে!'); window.location.href='affiliate.php';</script>";
    } else {
        echo "<script>alert('ট্রান্সফার ব্যর্থ হয়েছে!'); window.location.href='affiliate.php';</script>";
    }
} else {
    echo "<script>alert('আপনার কোনো কমিশন জমা নেই!'); window.location.href='affiliate.php';</script>";
}
ob_end_flush();
?>
