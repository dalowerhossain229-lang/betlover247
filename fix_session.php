<?php
// ১. সেশন এবং কুকি সেটিংস ঠিক করা
ini_set('session.cookie_domain', '.onrender.com'); 
session_start();

include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Session Fixer Tool</h2>";

// ২. আপনার ইউজারনেম দিয়ে সেশন ফোর্স করা
$test_user = "Abid123"; 

if (isset($_GET['login'])) {
    $_SESSION['username'] = $test_user;
    $_SESSION['user_id'] = $test_user;
    
    // ব্রাউজারে একটি পার্মানেন্ট কুকি সেট করা যাতে সেশন না থাকলেও কাজ করে
    setcookie("user_login", $test_user, time() + (86400 * 30), "/"); 
    
    echo "<p style='color:#00ff88;'>✅ সেশন এবং কুকি সফলভাবে সেট হয়েছে: <b>$test_user</b></p>";
    echo "<script>setTimeout(function(){ window.location.href='fix_session.php'; }, 2000);</script>";
}

// ৩. চেক করা
echo "<div style='border:1px solid #333; padding:15px; border-radius:10px;'>";
if (isset($_SESSION['username']) || isset($_COOKIE['user_login'])) {
    $current_user = $_SESSION['username'] ?? $_COOKIE['user_login'];
    echo "✅ আপনি এখন লগইন আছেন: <b style='color:#00ff88;'>$current_user</b><br><br>";
    echo "🔗 এখন <a href='play.php' style='background:#00ff88; color:#000; padding:5px 10px; border-radius:5px; text-decoration:none; font-weight:bold;'>গেম পেজে যান</a>";
} else {
    echo "<p style='color:red;'>❌ সেশন পাওয়া যায়নি!</p>";
    echo "<a href='fix_session.php?login=1' style='color:#00ff88; font-weight:bold;'>লগইন সেশন তৈরি করতে এখানে ক্লিক করুন</a>";
}
echo "</div>";

if (isset($_GET['clear'])) {
    session_destroy();
    setcookie("user_login", "", time() - 3600, "/");
    header("Location: fix_session.php");
}
echo "<br><br><a href='fix_session.php?clear=1' style='color:#ff4d4d;'>ক্লিয়ার করুন</a>";
echo "</body>";
?>
