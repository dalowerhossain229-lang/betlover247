<?php
session_start();
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🔍 Database Inspector & Fixer</h2>";

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';

if (empty($u)) {
    echo "<p style='color:red;'>❌ আপনি লগইন নেই! দয়া করে আগে লগইন করুন।</p>";
    exit();
}

// ২. ডাটাবেস থেকে বর্তমান তথ্য দেখা
$query = $conn->query("SELECT username, balance, main_t, t_main FROM users WHERE username = '$u'");
$data = $query->fetch_assoc();

echo "<div style='border:1px solid #333; padding:15px; border-radius:10px; background:#111;'>";
echo "<h3>📊 বর্তমান ডাটাবেস অবস্থা:</h3>";
echo "ইউজারনেম: <b style='color:#00ff88;'>" . $data['username'] . "</b><br>";
echo "ব্যালেন্স: ৳" . $data['balance'] . "<br>";
echo "বর্তমান খেলা (main_t): <b style='color:#ff4d4d;'>" . $data['main_t'] . "</b><br>";
echo "টার্গেট (t_main): <b style='color:#ffdf1b;'>" . $data['t_main'] . "</b> (এটিই আপনার উইথড্র পেজে দেখাচ্ছে)";
echo "</div>";

// ৩. যদি টার্গেট ১০০০ থাকে এবং আপনি ৭০০ করতে চান
if (isset($_GET['fix'])) {
    $new_target = 700;
    $conn->query("UPDATE users SET t_main = $new_target WHERE username = '$u'");
    echo "<p style='color:#00ff88; margin-top:20px;'>✅ Success! আপনার টার্গেট ১০০০ থেকে কমিয়ে <b>$new_target</b> করা হয়েছে।</p>";
    echo "<a href='withdraw.php' style='color:#fff;'>এখন উইথড্র পেজে গিয়ে চেক করুন</a>";
} else {
    echo "<br><br><a href='fix_withdraw.php?fix=1' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>টার্গেট ৭০০ করতে এখানে ক্লিক করুন</a>";
}

echo "</body>";
?>
