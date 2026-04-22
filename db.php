<?php
// ১. ডাটাবেস কানেকশন তথ্য
$host = "mysql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$port = 15764;
$user = "avnadmin";
$pass = "AVNS_g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// ২. ডাটাবেস কলাম অটো-ফিক্স (সব ভার্সনের জন্য নিরাপদ পদ্ধতি)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");

if ($check && $check->num_rows == 0) {
    // কলামগুলো না থাকলে একটি একটি করে যোগ করবে
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
    $conn->query("ALTER TABLE users ADD p_bkash VARCHAR(20) DEFAULT NULL");
    $conn->query("ALTER TABLE users ADD p_nagad VARCHAR(20) DEFAULT NULL");
}
?>
