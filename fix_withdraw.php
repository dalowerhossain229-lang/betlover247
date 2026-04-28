<?php
include 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ১. কলাম আছে কিনা চেক করে তৈরি করার নিরাপদ পদ্ধতি
function addColumn($conn, $table, $column, $type) {
    $check = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
    if ($check->num_rows == 0) {
        $conn->query("ALTER TABLE $table ADD $column $type");
        return "✅ কলাম '$column' তৈরি হয়েছে।<br>";
    }
    return "ℹ️ কলাম '$column' আগে থেকেই আছে।<br>";
}

echo addColumn($conn, 'users', 'main_t', "DECIMAL(10,2) DEFAULT 0.00");
echo addColumn($conn, 'users', 't_main', "DECIMAL(10,2) DEFAULT 1000.00");

// ২. আপনার লগইন করা ইউজারনেম অনুযায়ী টার্নওভার সেট করা
$u = $_SESSION['ABCD'] ?? ''; 
if (!empty($u)) {
    $update = $conn->query("UPDATE users SET main_t = 21189, t_main = 1000 WHERE username = '$u'");
    if ($update) {
        echo "<h2>✅ Success! আপনার টার্নওভার আপডেট হয়েছে।</h2>";
        echo "<a href='withdraw.php' style='padding:10px; background:green; color:white; text-decoration:none;'>উইথড্র পেজে যান</a>";
    }
} else {
    echo "<h2 style='color:red;'>⚠️ সেশন পাওয়া যায়নি! দয়া করে আগে লগইন করুন।</h2>";
}
?>
