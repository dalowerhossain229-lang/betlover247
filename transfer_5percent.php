<?php
session_start();
include 'db.php';

$u = $_SESSION['user_id'] ?? '';
if (empty($u)) { header("Location: index.php"); exit(); }

// ১. ইউজারের বর্তমান কমিশন চেক
$res = $conn->query("SELECT aff_balance FROM users WHERE username = '$u'");
$user = $res->fetch_assoc();
$amount = (float)$user['aff_balance'];

if ($amount > 0) {
    // ২. কমিশন ব্যালেন্স ০ করা এবং মেইন ব্যালেন্সে যোগ করা
    $conn->query("UPDATE users SET aff_balance = 0, balance = balance + $amount WHERE username = '$u'");
    
    echo "<script>alert('৳$amount সফলভাবে মেইন ব্যালেন্সে যোগ হয়েছে!'); window.location.href='affiliate.php';</script>";
} else {
    echo "<script>alert('আপনার কোনো কমিশন জমা নেই!'); window.location.href='affiliate.php';</script>";
}
?>
