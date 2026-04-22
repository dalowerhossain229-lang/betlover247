<?php
// ১. আপনার সঠিক ডাটাবেস তথ্য
$host = "mysql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$port = 15764;
$user = "avnadmin";
$pass = "AVNS_g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";

// ২. ডাটাবেস কানেকশন
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// কানেকশন চেক
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
