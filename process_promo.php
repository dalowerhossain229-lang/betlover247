<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// লগইন চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "আবেদন করতে আগে লগইন করুন!"]);
    exit;
}

$user = $_SESSION['user_id'];
$type = mysqli_real_escape_string($conn, $_POST['type'] ?? 'Unknown');
$details = mysqli_real_escape_string($conn, $_POST['details'] ?? '');

// ডাটাবেসে সেভ করার ইউনিক কি (Key) তৈরি
$config_key = "promo_" . $type . "_" . $user . "_" . time();
$config_value = "ইউজার: $user | তথ্য: $details";

// site_configs টেবিলে ডাটা ইনসার্ট করা
$sql = "INSERT INTO site_configs (config_key, config_value) VALUES ('$config_key', '$config_value')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "আপনার আবেদনটি এডমিন প্যানেলে পাঠানো হয়েছে!"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
