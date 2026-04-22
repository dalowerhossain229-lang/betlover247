<?php
// ১. আপনার সঠিক ডাটাবেস তথ্য (পোর্ট ১৫৭৬৪ সহ)
$host = "mysql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$user = "avnadmin";
$pass = "AVNS__g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";
$port = 15764; // আপনার দেওয়া সঠিক পোর্ট

// ২. ডাটাবেস কানেকশন
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// কানেকশন চেক
if ($conn->connect_error) {
    die("কানেকশন ফেল: " . $conn->connect_error);
}

// ৩. ডাটাবেস অটো-ফিক্স লজিক (উইথড্র এরর দূর করার জন্য)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check && $check->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
}
?>
