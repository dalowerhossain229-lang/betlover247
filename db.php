<?php
// ১. ডাটাবেস তথ্য (হোস্টনেমসহ)
$host = "://aivencloud.com";
$user = "mysql-ql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$pass = "AVNS__g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";
$port = 15768;

// ২. শক্তিশালী কানেকশন পদ্ধতি (SSL হ্যান্ডশেক ফিক্স)
$conn = mysqli_init();
mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

$success = mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$success) {
    die("কানেকশন এরর: " . mysqli_connect_error());
}

// ৩. ডাটাবেস অটো-ফিক্স লজিক (উইথড্র ও টার্নওভার এরর দূর করার জন্য)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check && $check->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
}
?>
