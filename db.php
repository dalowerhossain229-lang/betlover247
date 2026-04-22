<?php
// ১. ডাটাবেস তথ্য (পোর্ট ও হোস্ট আলাদা করা হয়েছে)
$host = "mysql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$user = "avnadmin";
$pass = "AVNS__g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";
$port = 15768;

// ২. এসএসএল মোড ব্যবহার করে কানেকশন (এভেনের জন্য বাধ্যতামূলক)
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL); 
$success = mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$success) {
    die("কানেকশন ফেল: " . mysqli_connect_error());
}

// ৩. ডাটাবেস অটো-ফিক্স লজিক (টার্নওভার সিস্টেমের জন্য)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check && $check->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
}
?>
