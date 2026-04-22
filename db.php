<?php
// ১. ডাটাবেস তথ্য (হোস্টনেমসহ)
$host ="mysql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$user = "avnadmin";
$pass = "AVNS__g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";
$port = 15764;

// ২. শক্তিশালী কানেকশন পদ্ধতি (SSL হ্যান্ডশেক ফিক্স)
$conn = mysql_init();
mysql_options($conn, MYSQL_OPT_SSL_VERIFY_SERVER_CERT, false);

$success = mysql_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQL_CLIENT_SSL);

if (!$success) {
    die("কানেকশন এরর: " . mysql_connect_error());
}

// ৩. ডাটাবেস অটো-ফিক্স লজিক (উইথড্র ও টার্নওভার এরর দূর করার জন্য)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check && $check->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
}
?>
