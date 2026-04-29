<?php
// ১. সেশন কনফিগারেশন (নিরাপদভাবে সেশন শুরু করা)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 86400); // সেশন ১ দিন স্থায়ী হবে
    ini_set('session.gc_maxlifetime', 86400);
    session_start();
}

include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Session Fixer Tool</h2>";

// ২. সেশন রিসেট করা (যদি কোনো এরর থাকে)
if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: fix_session.php");
    exit();
}

// ৩. আপনার ইউজারনেমটি এখানে দিন (টেস্ট করার জন্য সেশন সেট করা)
// আপনার ইউজারনেম 'Abid123' হলে সেটি দিন
$test_user = "Abid123"; 

if (isset($_GET['force_login'])) {
    $_SESSION['username'] = $test_user;
    $_SESSION['user_id'] = $test_user;
    echo "<p style='color:#00ff88;'>✅ সেশন সফলভাবে সেট করা হয়েছে: <b>$test_user</b></p>";
}

// ৪. বর্তমান অবস্থা চেক করা
echo "<div style='border:1px solid #333; padding:15px; border-radius:10px;'>";
if (isset($_SESSION['username'])) {
    echo "✅ আপনি এখন লগইন আছেন: <b style='color:#00ff88;'>" . $_SESSION['username'] . "</b><br>";
    echo "🔗 এখন <a href='play.php' style='color:#ffdf1b;'>গেম পেজে</a> যান, ইনশাল্লাহ আর Guest দেখাবে না।";
} else {
    echo "❌ আপনার কোনো সেশন পাওয়া যায়নি।<br>";
    echo "<a href='fix_session.php?force_login=1' style='color:#00ff88;'>লগইন সেশন তৈরি করতে এখানে ক্লিক করুন</a>";
}
echo "</div>";

echo "<br><br><a href='fix_session.php?reset=1' style='color:#ff4d4d;'>সেশন ক্লিয়ার/রিসেট করুন</a>";
echo "</body>";
?>
