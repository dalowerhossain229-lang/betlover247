<?php 
ob_start(); 
session_start();
include 'db.php';

// ১. পেজের ডিজাইন ও ব্যাকগ্রাউন্ড
echo "<body style='background:#000; color:#fff; font-family:sans-serif; text-align:center; padding:50px;'>";

// ২. সেশন থেকে ইউজার এবং ফরম থেকে ডাটা নেওয়া (REQUEST ব্যবহার করা হয়েছে সব ধরার জন্য)
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
$old = $_REQUEST['old_pass'] ?? $_REQUEST['current_password'] ?? '';
$new = $_REQUEST['new_pass'] ?? $_REQUEST['new_password'] ?? '';

// ৩. চেক: ঘর খালি আছে কি না
if (empty($u) || empty($old) || empty($new)) {
    echo "<div style='border:1px solid #ff4d4d; padding:20px; display:inline-block; border-radius:10px;'>";
    echo "<h2 style='color:#ff4d4d;'>❌ তথ্য পাওয়া যায়নি!</h2>";
    echo "<p>দয়া করে প্রোফাইল পেজে গিয়ে পাসওয়ার্ড সঠিকভাবে দিন।</p>";
    echo "<br><a href='profile.php' style='color:#fff;'>ফিরে যান</a>";
    echo "</div>";
} else {
    // ৪. ডাটাবেসে ইউজার এবং পুরনো পাসওয়ার্ড চেক (১২৩ এর মতো ছোট পাসওয়ার্ডও চিনবে)
    $q = "SELECT * FROM users WHERE username = '$u' AND password = '$old'";
    $res = $conn->query($q);

    if ($res && $res->num_rows > 0) {
        // ৫. পাসওয়ার্ড মিলে গেলে আপডেট করা
        $update_q = "UPDATE users SET password = '$new' WHERE username = '$u'";
        if ($conn->query($update_q)) {
            echo "<div style='border:1px solid #00ff88; padding:20px; display:inline-block; border-radius:10px;'>";
            echo "<h2 style='color:#00ff88;'>✅ পাসওয়ার্ড সফলভাবে পরিবর্তন হয়েছে!</h2>";
            echo "<p>আপনার নতুন পাসওয়ার্ড এখন সক্রিয়।</p>";
            echo "<br><a href='profile.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>প্রোফাইলে ফিরে যান</a>";
            echo "</div>";
        }
    } else {
        // ৬. পাসওয়ার্ড না মিললে
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
