<?php
session_start();
include 'db.php';

$u = $_SESSION['username'] ?? '';
$old_pass = $_POST['old_pass'] ?? '';
$new_pass = $_POST['new_pass'] ?? '';

if (!empty($u) && !empty($old_pass) && !empty($new_pass)) {
    // ১. চেক করা যে বর্তমান পাসওয়ার্ড ঠিক আছে কি না
    $query = $conn->query("SELECT password FROM users WHERE username = '$u'");
    $user = $query->fetch_assoc();

    if ($user['password'] === $old_pass) {
        // ২. নতুন পাসওয়ার্ড আপডেট করা
        $update = $conn->query("UPDATE users SET password = '$new_pass' WHERE username = '$u'");
        if ($update) {
            echo "<script>alert('✅ পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!'); window.location='profile.php';</script>";
        } else {
            echo "এরর: " . $conn->error;
        }
    } else {
        echo "<script>alert('❌ বর্তমান পাসওয়ার্ড ভুল!'); window.location='profile.php';</script>";
    }
} else {
    header("Location: profile.php");
}
?>
