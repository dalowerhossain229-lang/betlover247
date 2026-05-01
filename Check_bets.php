<?php
include 'db.php';
$res = $conn->query("SELECT * FROM bets ORDER BY id DESC LIMIT 5");
while($row = $res->fetch_assoc()){ echo "User in DB: " . $row['username'] . " | Bet: " . $row['bet_amount'] . "<br>"; }
?>
