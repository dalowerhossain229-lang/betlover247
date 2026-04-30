<?php 
ob_start(); 
session_start();
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; text-align:center; padding:50px;'>";

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? '';

// ২. প্রোফাইল পেজের নামের সাথে মিলিয়ে ডাটা নেওয়া
$old = $_POST['old_pass'] ?? '';
$new = $_POST['new_pass'] ?? '';

if (empty($u) || empty($old) || empty($new)) {
    echo "<div style='border:1px solid red; padding:20px; display:inline-block; border-radius:10px;'>";
    echo "<h2 style='color:red;'>❌ তথ্য পাওয়া যায়নি!</h2>";
    echo "<p>আপনি সঠিক পাসওয়ার্ড ইনপুট দেননি।</p>";
    echo "<br><a href='profile.php' style='color:#fff; text-decoration:underline;'>প্রোফাইলে ফিরে যান</a>";
    echo "</div>";
} else {
    // ৩. ডাটাবেস চেক
    $check = $conn->query("SELECT password FROM users WHERE username = '$u'");
    $user = $check->fetch_assoc();

    if ($user && $user['password'] === $old) {
        // ৪. সফল পরিবর্তন
        if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$u'")) {
            echo "<h2 style='color:#00ff88;'>✅ পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!</h2>";
            echo "<br><a href='profile.php' style='color:#ffdf1b; text-decoration:none;'>ফিরে যান</a>";
        }
    } else {
        // ৫. ভুল পাসওয়ার্ড
        echo "<h2 style='color:red;'>❌ বর্তমান পাসওয়ার্ডটি ভুল!</h2>";
        echo "<br><a href='profile.php' style='color:#fff;'>আবার চেষ্টা করুন</a>";
    }
}

echo "</body>";
ob_end_flush(); 
?>
