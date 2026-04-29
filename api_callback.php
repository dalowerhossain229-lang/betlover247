<?php
include 'db.php';
header('Content-Type: application/json');

// ১. এপিআই থেকে আসা ডেটা ধরা
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
    exit;
}

$action   = $data['action']; // bet, win, refund
$username = mysqli_real_escape_string($conn, $data['username']);
$amount   = floatval($data['amount'] ?? 0);
$game     = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Casino');

// ২. ইউজার বর্তমানে কোন ওয়ালেট সিলেক্ট করে খেলছে তা চেক করা
$u_sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found"]);
    exit;
}

// আপনার সিস্টেম অনুযায়ী এক্টিভ ওয়ালেট ধরা (ডিফল্ট: balance)
// আপনি যদি সেশনে বা ডাটাবেসে ওয়ালেট টাইপ সেভ করেন তবে সেটি এখানে কল করবেন
$wallet = $u_data['active_wallet'] ?? 'main'; 

if ($wallet == 'pb') {
    $bal_col = "pb_balance"; $turn_col = "pb_t";
} elseif ($wallet == 'bonus') {
    $bal_col = "bonus_balance"; $turn_col = "bonus_t";
} else {
    $bal_col = "balance"; $turn_col = "main_t";
}

// ৩. বেট করার লজিক (টাকা কাটা)
if ($action == "bet") {
    if ($u_data[$bal_col] >= $amount) {
        $conn->query("UPDATE users SET $bal_col = $bal_col - $amount, $turn_col = $turn_col + $amount WHERE username = '$username'");
        $conn->query("INSERT INTO game_logs (username, game_name, action, amount) VALUES ('$username', '$game', 'bet', '$amount')");
        echo json_encode(["status" => "ok", "message" => "Bet Accepted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Insufficient Balance"]);
    }
} 

// ৪. উইন করার লজিক (টাকা যোগ হওয়া)
elseif ($action == "win") {
    $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    $conn->query("INSERT INTO game_logs (username, game_name, action, amount) VALUES ('$username', '$game', 'win', '$amount')");
    echo json_encode(["status" => "ok", "message" => "Win Distributed"]);
}

// ৫. রিফান্ড লজিক
elseif ($action == "refund") {
    $conn->query("UPDATE users SET $bal_col = $bal_col + $amount WHERE username = '$username'");
    echo json_encode(["status" => "ok", "message" => "Refund Processed"]);
}
?>
