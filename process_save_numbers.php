<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. লগইন চেক করা
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আবার লগইন করুন!"]);
    exit;
}

$user = $_SESSION['user_id'];

// ২. ডাটাবেস থেকে ইউজারের বর্তমান নম্বরগুলো চেক করা
$res = $conn->query("SELECT p_bkash, p_nagad FROM users WHERE username = '$user'");
$check = $res->fetch_assoc();

$bkash = mysqli_real_escape_string($conn, $_POST['bkash']);
$nagad = mysqli_real_escape_string($conn, $_POST['nagad']);

// ৩. আপডেট করার জন্য কুয়েরি সাজানো (লক লজিক)
$updates = [];
if (empty($check['p_bkash']) && !empty($bkash)) {
    $updates[] = "p_bkash = '$bkash'";
}
if (empty($check['p_nagad']) && !empty($nagad)) {
    $updates[] = "p_nagad = '$nagad'";
}

// ৪. যদি নতুন কোনো নম্বর আপডেট করার থাকে
if (count($updates) > 0) {
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE username = '$user'";
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "নম্বরগুলো চিরস্থায়ীভাবে সেভ হয়েছে!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ডাটাবেস আপডেট এরর!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "আপনি ইতিমধ্যে নম্বর সেভ করেছেন অথবা কোনো নম্বর দেননি।"]);
}
?>
