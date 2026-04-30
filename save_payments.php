<?php
session_start();
include 'db.php';

$u = $_SESSION['username'] ?? '';
$bkash = $_POST['bkash'] ?? '';
$nagad = $_POST['nagad'] ?? '';

if (!empty($u)) {
    // আগে চেক করা যে নম্বর অলরেডি আছে কি না
    $check = $conn->query("SELECT bkash_number, nagad_number FROM users WHERE username = '$u'");
    $row = $check->fetch_assoc();

    // যদি নম্বর খালি থাকে তবেই আপডেট হবে
    if (empty($row['bkash_number']) || empty($row['nagad_number'])) {
        $sql = "UPDATE users SET bkash_number = '$bkash', nagad_number = '$nagad' WHERE username = '$u'";
        $conn->query($sql);
        header("Location: profile.php?msg=saved");
    } else {
        die("❌ আপনি ইতিমধ্যে নম্বর সেট করেছেন। এটি আর পরিবর্তন করা সম্ভব নয়।");
    }
}
?>
