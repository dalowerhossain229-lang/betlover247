<?php
// ১. আপনার সঠিক ডাটাবেস তথ্য (পোর্ট ১৫৭৬৪ সহ)
$host = "mysql-2bfdebf3-dalowerhossain229-37ae.g.aivencloud.com";
$user = "avnadmin";
$pass = "AVNS_g6bnEBL_NKJqBuj85HD";
$dbname = "defaultdb";
$port = 15764; // আপনার দেওয়া সঠিক পোর্ট

// ২. ডাটাবেস কানেকশন
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// কানেকশন চেক
if ($conn->connect_error) {
    die("কানেকশন ফেল: " . $conn->connect_error);
}
?>
