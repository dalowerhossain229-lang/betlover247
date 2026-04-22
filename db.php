<?php
// ১. আপনার Aiven ডাটাবেস তথ্য
$host = "mysql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$port = 15764;
$user = "avnadmin";
$pass = "AVNS_g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// ২. ডাটাবেস কলাম ফিক্স (এরর দিবে না)
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS turnover_target INT DEFAULT 1000");
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS turnover_completed INT DEFAULT 0");
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS p_bkash VARCHAR(20) DEFAULT NULL");
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS p_nagad VARCHAR(20) DEFAULT NULL");
?>
