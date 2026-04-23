<?php
ob_start();
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. ইনপুট নেওয়া এবং অদৃশ্য স্পেস পরিষ্কার করা
$username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim($_POST['username'])) : '';
$password = isset($_POST['password']) ? mysqli_real_escape_string($conn, trim($_POST['password'])) : '';

// ২. ইনপুট খালি কি না চেক করা
if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "ইউজারনেম এবং পাসওয়ার্ড উভয়ই লিখুন!"]);
    exit;
}

// ৩. ডাটাবেসে ইউজার খুঁজে বের করা (সরাসরি পাসওয়ার্ড ম্যাচিং)
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // ৪. সেশন ডাটা সেট করা (লগইন ধরে রাখার জন্য)
    $_SESSION['user_id'] = $user['username'];
    $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';

    // ৫. স্মার্ট রিডাইরেক্ট লজিক (রোল অনুযায়ী)
    $redirect_url = "profile.php"; // সাধারণ এবং অ্যাফিলিয়েট ইউজারদের জন্য ডিফল্ট

    if (isset($user['role'])) {
        if ($user['role'] == 'admin') {
            $redirect_url = "admin_panel.php";
        } elseif ($user['role'] == 'staff') {
            $redirect_url = "staff_panel.php";
        }
    }

    echo json_encode(["status" => "success", "redirect" => $redirect_url]);
} else {
    // ৬. ডাটা না মিললে সঠিক মেসেজ দেওয়া
    echo json_encode(["status" => "error", "message" => "আপনার দেওয়া ইউজারনেম বা পাসওয়ার্ডটি সঠিক নয়!"]);
}
ob_end_flush();
?>
