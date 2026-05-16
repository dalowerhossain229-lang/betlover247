<?php
include 'db.php';
header('Content-Type: application/json');

// ১. এভিয়েটোর থেকে আসা ডেটা ধরা
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
    exit;
}

$action = $data['action'];
$username = mysqli_real_escape_string($conn, $data['username']);
$amount = floatval($data['amount'] ?? 0);
$game = mysqli_real_escape_string($conn, $data['game_name'] ?? 'Casino');

// ২. ইউজারের বর্তমান ওয়ালেট সিলেক্ট করে ব্যালেন্স চেক করা
$u_sql = $conn->query("SELECT * FROM users WHERE username = '$username'");
$u_data = $u_sql->fetch_assoc();

if (!$u_data) {
    echo json_encode(["status" => "error", "message" => "User Not Found"]);
    exit;
}

// আপনার সিস্টেম অনুযায়ী ওয়ালেট নির্ধারণ
$wallet = $u_data['active_wallet'] ?? 'main';

if ($wallet == 'pb') {
    $bal_col = "pb_balance"; $turn_col = "pb_t";
} elseif ($wallet == 'bonus') {
    $bal_col = "bonus_balance"; $turn_col = "bonus_t";
} else {
    $bal_col = "balance"; $turn_col = "main_t";
}
// 🎰 ১. বাজি ধরার লজিক (api_callback.php এর বেট সেকশনে এটি রিপ্লেস করুন)
if ($action == "bet") {
    // 🛡️ অ্যান্টি-ডাবল ক্লিক প্রোটোকল: একই প্লেয়ারের একই বাজি ২ সেকেন্ডের মধ্যে ডুপ্লিকেট হলে তা ব্লক করা হবে
    $check_dup = $conn->query("SELECT id FROM bets WHERE username = '$username' AND amount = '$amount' AND status = 'bet' AND created_at >= NOW() - INTERVAL 2 SECOND LIMIT 1");
    
    if ($check_dup && $check_dup->num_rows > 0) {
        // ডুপ্লিকেট রিকোয়েস্ট হলে ডাটাবেজে নতুন করে রো তৈরি না করে সরাসরি সাকসেস রিটার্ন করবে
        echo json_encode(["status" => "ok", "message" => "Duplicate Bypass", "balance" => floatval($u_data['balance'])]);
        exit;
    }

    // ডুপ্লিকেট না হলে মেইন ব্যালেন্স থেকে টাকা কাটবে
    $update = $conn->query("UPDATE users SET balance = balance - $amount, main_t = main_t + $amount WHERE username = '$username'");
    
    if ($update) {
        // bets টেবিলে বাজি ইনসার্ট করা
        $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$username', '$amount', 'Aviator', 'bet')");
        
        $current_bal = floatval($u_data['balance']) - $amount;
        echo json_encode(["status" => "ok", "message" => "Bet Accepted", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}

// 💰 ২. ক্যাশআউট বা জেতার লজিক (api_callback.php এর উইন সেকশনে এটি রিপ্লেস করুন)
elseif ($action == "win") {
    $update = $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$username'");
    
    if ($update) {
        // এভিয়েটর থেকে আসা bet_amount এবং পরিমাণ ধরে নির্দিষ্ট বাজিটিকে 'win' এ রূপান্তর করা হলো
        $bet_amount = floatval($data['bet_amount'] ?? 0);
        $conn->query("UPDATE bets SET status = 'win', amount = '$amount' WHERE username = '$username' AND amount = '$bet_amount' AND status = 'bet' ORDER BY id DESC LIMIT 1");
        
        $current_bal = floatval($u_data['balance']) + $amount;
        echo json_encode(["status" => "ok", "message" => "Win Distributed", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}

// 🔄 রিফান্ড লজিক
elseif ($action == "refund") {
    $update = $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$username'");
    if ($update) {
        $current_bal = floatval($u_data['balance']) + $amount;
        echo json_encode(["status" => "ok", "message" => "Refund Processed", "balance" => $current_bal]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database Update Failed"]);
    }
}
// 🔴 ৩. লস লজিক (api_callback.php এর লস সেকশনে এটি রিপ্লেস করুন)
elseif ($action == "loss") {
    // 📝 LIMIT 1 সরিয়ে দেওয়া হলো যাতে ক্রাশ খাওয়ার সাথে সাথে সব একটিভ বাজি একসাথে 'loss' হয়ে যায়
    $conn->query("UPDATE bets SET status = 'loss' WHERE username = '$username' AND status = 'bet'");
    echo json_encode(["status" => "ok", "message" => "Loss Recorded"]);
}
?>


