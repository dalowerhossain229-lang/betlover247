<?php
session_start();
include 'db.php';

// ১. লগইন চেক
$user_id = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আগে লগইন করুন!"]);
    exit;
}

// ২. ইনপুট ডেটা ধরা (POST মেথড ব্যবহার করা হয়েছে)
$bet_amount = isset($_POST['amount']) ? intval($_POST['amount']) : 10;
$wallet = isset($_POST['wallet']) ? $_POST['wallet'] : 'main';

if ($bet_amount <= 0) {
    echo json_encode(["status" => "error", "message" => "ভুল অ্যামাউন্ট!"]);
    exit;
}

// ৩. ওয়ালেট অনুযায়ী ডাটাবেস কলাম সিলেক্ট করা
if ($wallet == 'pb') {
    $bal_col = "pb_balance";
    $turn_col = "pb_t";
} elseif ($wallet == 'bonus') {
    $bal_col = "bonus_balance";
    $turn_col = "bonus_t";
} else {
    $bal_col = "balance";
    $turn_col = "main_t";
}

// ৪. ইউজারের ব্যালেন্স চেক করা
$res = $conn->query("SELECT $bal_col FROM users WHERE username = '$user_id' OR id = '$user_id'");
$u_data = $res->fetch_assoc();

if (!$u_data || $u_data[$bal_col] < $bet_amount) {
    echo json_encode(["status" => "error", "message" => "আপনার পর্যাপ্ত ব্যালেন্স নেই!"]);
    exit;
}

// ৫. ব্যালেন্স কাটা এবং টার্নওভার আপডেট করা
// আপনার ডাটাবেসের কলাম অনুযায়ী এটি সাজানো হয়েছে
$sql = "UPDATE users SET 
        $bal_col = $bal_col - $bet_amount, 
        $turn_col = $turn_col + $bet_amount 
        WHERE username = '$user_id' OR id = '$user_id'";

if ($conn->query($sql)) {
    // ৬. বেট হিস্টোরিতে রেকর্ড রাখা (ঐচ্ছিক)
    $conn->query("INSERT INTO game_history (username, game_name, wallet_type, bet_amount) VALUES ('$user_id', '2048 Game', '$wallet', $bet_amount)");
    
    echo json_encode([
        "status" => "success",
        "message" => "বাজি সফলভাবে ধরা হয়েছে!",
        "new_balance" => ($u_data[$bal_col] - $bet_amount)
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
