<?php 
ob_start(); 
session_start();
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; text-align:center; padding:50px;'>";

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? '';

// ২. ডাটা ধরার জন্য REQUEST ব্যবহার করা হলো (এটি POST এবং GET দুইটাই ধরে)
$old = $_REQUEST['old_pass'] ?? '';
$new = $_REQUEST['new_pass'] ?? '';

// ৩. ছোট একটি ট্রিক: যদি ডাটা না পায় তবে ইউজারের কাছে সরাসরি জানতে চাওয়া
if (empty($old) || empty($new)) {
    echo "<div style='border:1px solid red; padding:20px; display:inline-block; border-radius:10px;'>";
    echo "<h2 style='color:red;'>❌ ডাটা সিঙ্কিং সমস্যা!</h2>";
    echo "<p>সার্ভার আপনার পাঠানো পাসওয়ার্ড খুঁজে পাচ্ছে না।</p>";
    echo "<br><a href='profile.php' style='color:#fff;'>আবার ট্রাই করুন</a>";
    echo "</div>";
} else {
    // ৪. ডাটাবেস চেক ও আপডেট
    $res = $conn->query("SELECT password FROM users WHERE username = '$u'");
    $user = $res->fetch_assoc();

    if ($user && $user['password'] === $old) {
        if ($conn->query("UPDATE users SET password = '$new' WHERE username = '$u'")) {
            echo "<h2 style='color:#00ff88;'>✅ পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!</h2>";
            echo "<br><a href='profile.php' style='color:#ffdf1b; text-decoration:none;'>প্রোফাইলে ফিরে যান</a>";
        }
    } else {
        echo "<h2 style='color:red;'>❌ বর্তমান পাসওয়ার্ডটি ভুল!</h2>";
        echo "<br><a href='profile.php' style='color:#fff;'>আবার চেষ্টা করুন</a>";
    }
}

echo "</body>";
ob_end_flush(); 
?>
