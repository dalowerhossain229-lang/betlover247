<?php
include 'db.php'; // আপনার ডাটাবেস কানেকশন ফাইল

// ১. ডাটাবেসে প্রয়োজনীয় কলামগুলো আছে কি না চেক করা
$columns = [
    'main_t' => "DECIMAL(10,2) DEFAULT 0.00",
    't_main' => "DECIMAL(10,2) DEFAULT 1000.00"
];

foreach ($columns as $col => $type) {
    $check = $conn->query("SHOW COLUMNS FROM users LIKE '$col'");
    if ($check->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD $col $type");
        echo "✅ Column '$col' created.<br>";
    }
}

// ২. টেস্ট ইউজার আপডেট (আপনার টার্নওভার ২১১৮৯ সেট করা)
// এখানে 'your_username' এর জায়গায় আপনার ইউজারনেম দিন
$u = 'your_username'; 
$fix_user = $conn->query("UPDATE users SET main_t = 21189, t_main = 1000 WHERE username = '$u'");

if($fix_user) {
    echo "<h2>✅ Fix Successful!</h2>";
    echo "এখন উইথড্র পেজ রিফ্রেশ করে দেখুন, লাল বক্স চলে যাবে।";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
