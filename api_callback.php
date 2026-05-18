<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);
if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

$action = $data['action'];
$username = !empty($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
if (empty($username) && isset($_SESSION['user_id'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['user_id']);
}

$amount = floatval($data['amount'] ?? 0);
if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Empty User"]);
    exit;
}

$u_sql = $conn->query("SELECT * FROM users WHERE LOWER(username) = LOWER('$username')");
$u_data = $u_sql->fetch_assoc();
if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found"]);
    exit;
}

// 🎯 আপনার ডাটাবেজ কলামের নামের সাথে মিল রেখে স্মার্ট অটো-ওয়ালেট ডিটেক্টর
$pb_bal = floatval($u_data['pb_balance'] ?? 0);
$bonus_bal = floatval($u_data['bonus_balance'] ?? 0);
$main_bal = floatval($u_data['balance'] ?? 0);

if ($pb_bal >= $amount) {
    $bal_col = "pb_balance"; $turn_col = "pb_t"; $user_current_balance = $pb_bal;
} elseif ($bonus_bal >= $amount) {
    $bal_col = "bonus_balance"; $turn_col = "bonus_t"; $user_current_balance = $bonus_bal;
} else {
    $bal_col = "balance"; $turn_col = "t_main"; $user_current_balance = $main_bal;
}

// 🎰 বাজি ধরার লজিক
if ($action == "bet") {
    if ($user_current_balance < $amount) {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance!"]);
        exit;
    }
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '{$u_data['username']}'");
    if ($update) {
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('{$u_data['username']}', '$amount', 'Aviator', 'pending')");
        echo json_encode(["status" => "ok", "balance" => $user_current_balance - $amount]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Error"]);
    }
}
// 💰 ক্যাশআউট বা জেতার লজিক
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '{$u_data['username']}'");
    if ($update) {
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '{$u_data['username']}' AND status = 'pending'");
        echo json_encode(["status" => "ok", "balance" => $user_current_balance + $amount]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Error"]);
    }
}
// 🔴 লস লজিক
elseif ($action == "loss") {
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '{$u_data['username']}' AND status = 'pending'");
    echo json_encode(["status" => "ok"]);
}
?>
