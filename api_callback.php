<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';
header('Content-Type: application/json');

// ১. এভিয়েটোর থেকে আসা রিকোয়েস্ট ডাটা পড়া
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request JSON"]);
    exit;
}

$action = $data['action'];
$username = !empty($data['username']) ? mysqli_real_escape_string($conn, $data['username']) : '';
if (empty($username) && isset($_SESSION['username'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
}

$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Aviator');

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Username empty"]);
    exit;
}

// ২. ডাটাবেজ থেকে ইউজারের তথ্য আনা
$u_sql = $conn->query("SELECT * FROM users WHERE LOWER(username) = LOWER('$username')");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

// 🎯 ৩. স্মার্ট ওয়ালেট ডিটেকশন (প্লেয়ারের যে ওয়ালেটে টাকা আছে সেখান থেকেই কাটবে, কোনো এরর দেখাবে না)
$wallet = strtolower($data['wallet'] ?? $u_data['active_wallet'] ?? 'main');

if ($wallet == 'pb' || floatval($u_data['pb_balance']) >= $amount) {
    $bal_col = "pb_balance"; $turn_col = "pb_t";
} elseif ($wallet == 'bonus' || floatval($u_data['bonus_balance']) >= $amount) {
    $bal_col = "bonus_balance"; $turn_col = "bonus_t";
} else {
    $bal_col = "balance"; $turn_col = "main_t";
}

$user_current_balance = floatval($u_data[$bal_col] ?? 0);

// 🎰 বাজি ধরার লজিক
if ($action == "bet") {
    // অ্যান্টি-ডাবল ক্লিক প্রোটেকশন
    $check_dup = $conn->query("SELECT id FROM bets WHERE username = '$username' AND amount = '$amount' AND status = 'bet' AND created_at >= NOW() - INTERVAL 2 SECOND LIMIT 1");
    if ($check_dup && $check_dup->num_rows > 0) {
        echo json_encode(["status" => "ok", "message" => "Duplicate Bypass", "balance" => $user_current_balance]);
        exit;
    }

    // ব্যালেন্স আপডেট কুয়েরি
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '$username'");
    
    if ($update) {
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$username', '$amount', 'Aviator', 'bet')");
        $new_balance = $user_current_balance - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Update Failed"]);
    }
}
// 💰 ক্যাশআউট বা জেতার লজিক
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    
    if ($update) {
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '$username' AND status = 'bet'");
        $new_balance = $user_current_balance + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $new_balance]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Win Failed"]);
    }
}
// 🔴 লস লজিক
elseif ($action == "loss") {
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '$username' AND status = 'bet'");
    echo json_encode(["status" => "ok", "message" => "Loss Recorded"]);
}
?>
