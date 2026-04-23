<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "সেশন আউট! আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
$old = trim($_POST['old_pass'] ?? '');
$new = trim($_POST['new_pass'] ?? '');

// ১. ডাটাবেস থেকে তথ্য আনা (Username বা ID উভয় দিয়েই চেক করবে)
$res = $conn->query("SELECT password FROM users WHERE username = '$user' OR id = '$user' LIMIT 1");

if ($res && $res->num_rows > 0) {
    $u_data = $res->fetch_assoc();
    $db_pass = trim($u_data['password']);

    // ২. পাসওয়ার্ড সরাসরি মেলাবো (Plain Text)
    if ($old === $db_pass) {
        // ৩. নতুন পাসওয়ার্ড আপডেট
        if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$user' OR id = '$user'")) {
            echo json_encode(["status" => "success", "message" => "পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "আপনার দেওয়া পুরাতন পাসওয়ার্ডটি সঠিক নয়।"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ইউজার ডাটা পাওয়া যায়নি।"]);
}
?>
