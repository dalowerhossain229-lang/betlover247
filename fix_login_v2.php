<?php
include 'db.php';

echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px; background:#f0fdf4; padding:30px; border-radius:15px; max-width:600px; margin-left:auto; margin-right:auto; border:1px solid #bbf7d0;'>";

// ১. পাসওয়ার্ড কলামের স্পেস এবং টাইপ ফিক্স করা
$sql1 = "ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL";
$sql2 = "UPDATE users SET password = TRIM(password), username = TRIM(username)";

if($conn->query($sql1) && $conn->query($sql2)) {
    echo "<h1 style='color:#166534;'>🎉 ডাটাবেস ১০০০% ফিক্স হয়েছে!</h1>";
    echo "<p style='color:#1e293b;'>ইউজারনেম এবং পাসওয়ার্ডের সব অদৃশ্য স্পেস পরিষ্কার করা হয়েছে।</p>";
    echo "<hr style='border:0.5px solid #bbf7d0; margin:20px 0;'>";
    
    // ২. আপনার বর্তমান ইউজারদের একটি লিস্ট দেখানো (যাতে আপনি বানান চেক করতে পারেন)
    $users = $conn->query("SELECT username, password FROM users LIMIT 10");
    echo "<div style='text-align:left; background:white; padding:15px; border-radius:8px; border:1px solid #ddd;'>";
    echo "<b style='color:#166534;'>আপনার ইউজার লিস্ট (চেক করুন):</b><br><br>";
    while($row = $users->fetch_assoc()) {
        echo "👤 ইউজার: <span style='color:blue;'>" . $row['username'] . "</span> | 🔑 পাস: <span style='color:red;'>" . $row['password'] . "</span><br>";
    }
    echo "</div>";
    
    echo "<p style='margin-top:25px;'><a href='index.php' style='background:#16a34a; color:white; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold; box-shadow:0 4px 10px rgba(22,163,74,0.3);'>এখন লগইন করুন</a></p>";
} else {
    echo "<h1 style='color:#991b1b;'>❌ এরর: " . $conn->error . "</h1>";
}

echo "</div>";
?>
