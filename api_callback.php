<?php
// ১. সেশন রিস্টার্ট নিশ্চিত করা (আইফ্রেম সাপোর্ট সহ)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';
header('Content-Type: application/json');

// ২. এভিয়েটোর থেকে আসা রিকোয়েস্ট ডাটা পড়া
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
    exit;
}

$action = $data['action'];

// 🎯 ডাইনামিক ইউজার ট্র্যাকিং ফিক্স:
// যদি সেশন খালি থাকে, তবে এভিয়েটরের লিঙ্ক থেকে আসা সরাসরি ইউজারনেমটি ব্যবহার করবে
$username = !empty($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
if (empty($username) && isset($_SESSION['username'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
}

$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Aviator');

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Empty Username Parameter"]);
    exit;
}

// ৩. ডাটাবেজ থেকে ইউজারের ওয়ালেট এবং ব্যালেন্সের তথ্য আনা
// (এখানে ইউজারনেম দিয়ে ডাইনামিক কোয়েরি চালানো হচ্ছে)
$u_sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found In DB for " . $username]);
    exit;
}

// 🎯 ডাইনামিক ওয়ালেট সিলেকশন কলাম চেক
$wallet = $u_data['active_wallet'] ?? 'main';
