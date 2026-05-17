<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request JSON"]);
    exit;
}

$action = $data['action'];

// 🎯 সেশন ট্র্যাকিং ফিক্স: আপনার ডাটাবেজ সেশনের 'user_id' কী-টি এখানে যুক্ত করা হলো
$username = !empty($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
if (empty($username) && isset($_SESSION['user_id'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['user_id']);
}

$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Aviator');

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Empty Username Parameter"]);
    exit;
}

// ডাটাবেজ থেকে ইউজারের সঠিক তথ্য আনা (Case-Insensitive)
$u_sql = $conn->query("SELECT * FROM users WHERE LOWER(username) = LOWER('$username')");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found in DB for " . $username]);
    exit;
}

// 스마트 ওয়ালেট ডিটেক্টর লজিক
$pb_bal = floatval($u_data['pb_balance'] ?? 0);
$bonus_bal = floatval($u_data['bonus_balance'] ?? 0);
$main_bal = floatval($u_data['balance'] ?? 0);

if ($pb_bal >= $amount) {
    $bal_col = "pb_balance"; $turn_col = "pb_t";
    $user_current_balance = $pb_bal;
} elseif ($bonus_bal >= $amount) {
    $bal_col = "bonus_balance"; $turn_col = "bonus_t";
    $user_current_balance = $bonus_bal;
} else {
    $bal_col = "balance"; $turn_col = "t_main";
    $user_current_balance = $main_bal;
}

// 🎰 বাজি ধরার লজিক
if ($action == "bet") {
    $check_dup = $conn->query("SELECT id FROM bets WHERE username = '$username' AND amount = '$amount' AND status = 'bet' AND created_at >= NOW() - INTERVAL 2 SECOND LIMIT 1");
    if ($check_dup && $check_dup->num_rows > 0) {
        echo json_encode(["status" => "ok", "message" => "Duplicate Bypass", "balance" => $user_current_balance]);
        exit;
    }

    if ($user_current_balance < $amount) {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance!"]);
        exit;
    }

    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '$username'");
    
    if ($update) {
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$username', '$amount', 'Aviator', 'bet')");
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 💰 ক্যাশআউট লজিক
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    
    if ($update) {
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '$username' AND status = 'bet'");
        $new_balance = $user_current_balance + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔴 লস লজিক
elseif ($action == "loss") {
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '$username' AND status = 'bet'");
    echo json_encode(["status" => "ok", "message" => "Loss Recorded"]);
}
?>
