<?php 
ob_start(); 
session_start();
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; text-align:center; padding:50px;'>";

$u = $_SESSION['username'] ?? '';
$old_pass = $_POST['old_pass'] ?? '';
$new_pass = $_POST['new_pass'] ?? '';

// ১. কোনো ঘর খালি আছে কি না চেক করা
if (empty($u) || empty($old_pass) || empty($new_pass)) {
    echo "<h2 style='color:red;'>❌ সব ঘর পূরণ করুন!</h2>";
    echo "<br><a href='profile.php' style='color:#fff;'>ফিরে যান</a>";
} else {
    // ২. ডাটাবেস থেকে বর্তমান পাসওয়ার্ড চেক করা
    $query = $conn->query("SELECT password FROM users WHERE username = '$u'");
    $user = $query->fetch_assoc();

    if ($user['password'] === $old_pass) {
        // ৩. পাসওয়ার্ড মিলে গেলে আপডেট করা
        if ($conn->query("UPDATE users SET password = '$new_pass' WHERE username = '$u'")) {
            echo "<h2 style='color:#00ff88;'>✅ পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!</h2>";
            echo "<p>এখন নতুন পাসওয়ার্ড দিয়ে লগইন করতে পারবেন।</p>";
            echo "<br><a href='profile.php' style='color:#ffdf1b;'>প্রোফাইলে ফিরে যান</a>";
        }
    } else {
        // ৪. পাসওয়ার্ড না মিললে
        echo "<h2 style='color:red;'>❌ বর্তমান পাসওয়ার্ডটি ভুল!</h2>";
        echo "<br><a href='profile.php' style='color:#fff;'>আবার চেষ্টা করুন</a>";
    }
}

echo "</body>";
ob_end_flush(); 
?>
