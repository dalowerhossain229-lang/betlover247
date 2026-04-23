<?php
ob_start();
session_start();
include 'db.php';
header('Content-Type: application/json');

$username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim($_POST['username'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "ইউজারনেম এবং পাসওয়ার্ড উভয়ই লিখুন!"]);
    exit;
}

// ১. ডাটাবেসে ইউজারকে খুঁজে বের করা
$query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $db_pass = $user['password'];

    // ২. পাসওয়ার্ড যাচাই (Hash এবং Plain Text উভয়ই চেক করবে)
    $is_valid = false;
    if ($password === $db_pass) {
        $is_valid = true; // সরাসরি টেক্সট মিলে গেলে
    } elseif (password_verify($password, $db_pass)) {
        $is_valid = true; // হ্যাস করা পাসওয়ার্ড মিলে গেলে
    }

    if ($is_valid) {
        $_SESSION['user_id'] = $user['username'];
        $_SESSION['role'] = $user['role'] ?? 'user';

        $redirect_url = "profile.php";
        if (($user['role'] ?? '') == 'admin') $redirect_url = "admin_panel.php";
        elseif (($user['role'] ?? '') == 'staff') $redirect_url = "staff_panel.php";

        echo json_encode(["status" => "success", "redirect" => $redirect_url]);
    } else {
        echo json_encode(["status" => "error", "message" => "পাসওয়ার্ডটি সঠিক নয়!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "এই ইউজারনেমটি খুঁজে পাওয়া যায়নি!"]);
}
ob_end_flush();
?>
