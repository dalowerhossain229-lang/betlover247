<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "আগে লগইন করুন!"]); exit;
}

$type = $_POST['promo_type'] ?? '';
$details = $_POST['details'] ?? ''; 
$userId = $_SESSION['user_id'];

// ডাটাবেসে (promo_applications টেবিল) সেভ করা
$stmt = $conn->prepare("INSERT INTO promo_applications (user_id, promo_type, details) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $userId, $type, $details);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "আপনার আবেদনটি সফলভাবে জমা হয়েছে!"]);
} else {
    echo json_encode(["status" => "error", "message" => "সার্ভার এরর! ডাটাবেস টেবিল চেক করুন।"]);
}
?>
