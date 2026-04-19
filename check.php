<?php
include 'db.php';
$res = $conn->query("SELECT * FROM users");
while($row = $res->fetch_assoc()) {
    echo "User: " . $row['username'] . " | Balance: " . $row['balance'] . "<br>";
}
?>
