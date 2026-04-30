<?php 
ob_start(); 
session_start();
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; text-align:center; padding:50px;'>";

$u = $_SESSION['username'] ?? '';
$old_pass = $_POST['old_pass'] ?? '';
$new_pass = $_POST['new_pass'] ?? '';

if (!empty($u) && !empty($old_pass) && !empty($new_pass)) {
    // ১. ডাটাবেস থেকে ইউজারের বর্তমান পাসওয়ার্ড আনা
    $query = $conn->query("SELECT password FROM users WHERE username = '$u'");
    $user = $query->fetch_assoc();

    if ($user['password'] === $old_pass) {
        // ২. পাসওয়ার্ড আপডেট করা
        if ($conn->query("UPDATE users SET password = '$new_pass' WHERE username = '$u'")) {
            echo "<div style='border:1px solid #00ff88; padding:30px; border-radius:15px; display:inline-block;'>";
            echo "<h2 style='color:#00ff88;'>✅ পাসওয়ার্ড পরিবর্তন সফল!</h2>";
            echo "<p>আপনার নতুন পাসওয়ার্ডটি সেভ করা হয়েছে।</p>";
            echo "<br><a href='profile.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>প্রোফাইলে ফিরে যান</a>";
            echo "</div>";
        }
    } else {
        echo "<div style='border:1px solid #ff4d4d; padding:30px; border-radius:15px; display:inline-block;'>";
        echo "<h2 style='color:#ff4d4d;'>❌ ভুল পাসওয়ার্ড!</h2>";
        echo "<p>আপনার দেওয়া বর্তমান পাসওয়ার্ডটি সঠিক নয়।</p>";
        echo "<br><a href='profile.php' style='background:#ff4d4d; color:#fff; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>আবার চেষ্টা করুন</a>";
        echo "</div>";
    }
} else {
    header("Location: profile.php");
}
echo "</body>";
<?php ob_end_flush(); ?>

