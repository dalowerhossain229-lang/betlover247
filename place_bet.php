<?php
ob_start();
session_start();
include 'db.php';
header('Content-Type: application/json');

$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
$amount = 10;
$wallet = $_POST['wallet'] ?? 'main';

if (empty($u)) {
    die(json_encode(["status" => "error", "message" => "No Session"]));
}

if ($wallet == 'pb') { $col = "pb_balance"; $t_col = "pb_t"; }
elseif ($wallet == 'bonus') { $col = "bonus_balance"; $t_col = "bonus_t"; }
else { $col = "balance"; $t_col = "main_t"; }

$sql = "UPDATE users SET $col = $col - $amount, $t_col = $t_col + $amount WHERE username = '$u' OR id = '$u'";

if ($conn->query($sql)) {
    $conn->query("INSERT INTO game_history (username, game_name, wallet_type, bet_amount) VALUES ('$u', '2048 Game', '$wallet', $amount)");
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
ob_end_flush();
?>
