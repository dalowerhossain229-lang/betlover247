<?php 
ob_start(); 
session_start();
include 'db.php';

// পেজের ডিজাইন
echo "<body style='background:#000; color:#fff; font-family:sans-serif; text-align:center; padding:50px;'>";

// ১. সেশন থেকে ইউজার এবং ফরম থেকে ডাটা নেওয়া
$u = $_SESSION['username'] ?? '';

// সম্ভাব্য সব ধরণের ইনপুট নাম চেক করা (আপনার ফরমের সাথে মিল রাখতে)
$old_pass = $_POST['current_password'] ?? $_POST['old_pass'] ?? $_POST['oldpassword'] ?? '';
$new_pass = $_POST['new_password'] ?? $_POST['new_pass'] ?? $_POST['newpassword'] ?? '';

// ২. কোনো ঘর খালি আছে কি না চেক করা
if (empty($u) || empty($old_pass) || empty($new_pass)) {
    echo "<div style='border:1px solid red; padding:20px; display:inline-block; border-radius:10px;'>";
    echo "<h2 style='color:red;'>❌ তথ্য পাওয়া যায়নি!</h2>";
    echo "<p>দয়া করে প্রোফাইল পেজে গিয়ে পাসওয়ার্ড লিখে 'আপডেট' বাটনে ক্লিক করুন।</p>";
    echo "<br><a href='profile.php' style='background:#fff; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>ফিরে যান</a>";
    echo "</div>";
} else {
    // ৩. ডাটাবেস থেকে বর্তমান পাসওয়ার্ড চেক করা
    $query = $conn->query("SELECT password FROM users WHERE username = '$u'");
    $user = $query->fetch_assoc();

    if ($user && $user['password'] === $old_pass) {
        // ৪. পাসওয়ার্ড মিলে গেলে আপডেট করা
        if ($conn->query("UPDATE users SET password = '$new_pass' WHERE username = '$u'")) {
            echo "<div style='border:1px solid #00ff88; padding:20px; display:inline-block; border-radius:10px;'>";
            echo "<h2 style='color:#00ff88;'>✅ পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!</h2>";
            echo "<p>আপনার নতুন পাসওয়ার্ড এখন সক্রিয়।</p>";
            echo "<br><a href='profile.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>প্রোফাইলে ফিরে যান</a>";
            echo "</div>";
        }
    } else {
        // ৫. পাসওয়ার্ড না মিললে
        echo "<div style='border:1px solid #ff4d4d; padding:20px; display:inline-block; border-radius:10px;'>";
        echo "<h2 style='color:#ff4d4d;'>❌ বর্তমান পাসওয়ার্ডটি ভুল!</h2>";
        echo "<p>আপনার দেওয়া পুরনো পাসওয়ার্ডটি ডাটাবেসের সাথে মিলছে না।</p>";
        echo "<br><a href='profile.php' style='color:#fff; text-decoration:underline;'>আবার চেষ্টা করুন</a>";
        echo "</div>";
    }
}

echo "</body>";
ob_end_flush(); 
?>
