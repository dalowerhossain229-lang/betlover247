<?php
include 'db.php';

// ১. আপনার ইউজারনেমটি এখানে হুবহু লিখুন (যেমন আপনার ইউজারনেম যদি ABCD হয় তবে ABCD লিখুন)
$username = 'আপনার_ইউজারনেম'; 

// ২. সরাসরি আপনার আইডিতে টার্নওভার সেট করে দেওয়া
$update = $conn->query("UPDATE users SET main_t = 21189, t_main = 1000 WHERE username = '$username'");

if ($update && $conn->affected_rows > 0) {
    echo "<h2 style='color:green;'>✅ Success! আপনার অ্যাকাউন্টে টার্নওভার সেট হয়েছে।</h2>";
    echo "<p>এখন উইথড্র পেজ ওপেন করলে লাল বক্স থাকবে না।</p>";
    echo "<a href='withdraw.php' style='padding:10px; background:blue; color:white; border-radius:5px; text-decoration:none;'>উইথড্র পেজে যান</a>";
} else {
    echo "<h2 style='color:red;'>❌ ভুল হয়েছে!</h2>";
    echo "আপনার ইউজারনেম সম্ভবত ডাটাবেসের সাথে মিলছে না। <br>আপনার ডাটাবেস এরর: " . $conn->error;
}
?>
