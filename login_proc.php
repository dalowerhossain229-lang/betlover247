<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. ইনপুট সংগ্রহ ও পরিষ্কার করা
$user = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
$pass = mysqli_real_escape_string($conn, trim($_POST['password'] ?? ''));

if (empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "ইউজারনেম এবং পাসওয়ার্ড উভয়ই প্রয়োজন!"]);
    exit;
}

// ২. ডাটাবেসে ইউজার খুঁজে বের করা (সব কলামসহ)
$sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass' LIMIT 1";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $u_data = $res->fetch_assoc();
    
    // ৩. সেশন সেটআপ
    $_SESSION['user_id'] = $u_data['username'];
    $_SESSION['role'] = $u_data['role'] ?? 'user';

    // ৪. স্মার্ট রিডাইরেক্ট লজিক (অ্যাডমিন, স্টাফ এবং এফিলিয়েট সবার জন্য)
    $redirect = "profile.php"; // ডিফল্ট গন্তব্য

    if (isset($u_data['role'])) {
        if ($u_data['role'] == 'admin') {
            $redirect = "admin_panel.php";
        } elseif ($u_data['role'] == 'staff') {
            $redirect = "staff_panel.php";
        }
    }

    // ৫. সফল লগইন রেসপন্স
    echo json_encode(["status" => "success", "redirect" => $redirect]);
} else {
    // ডাটা না মিললে এরর মেসেজ
    echo json_encode(["status" => "error", "message" => "আপনার দেওয়া ইউজারনেম বা পাসওয়ার্ডটি সঠিক নয়!"]);
}
?>
