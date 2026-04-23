<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আগে লগইন করুন!"]);
    exit;
}

$user = $_SESSION['user_id'];
$bet_amount = intval($_POST['amount'] ?? 0); // বাজির পরিমাণ
$game_id = mysqli_real_escape_string($conn, $_POST['game_id'] ?? '');

// ২. বাজির অ্যামাউন্ট চেক
if ($bet_amount <= 0) {
    echo json_encode(["status" => "error", "message" => "ভুল অ্যামাউন্ট!"]);
    exit;
}

// ৩. ইউজারের ব্যালেন্স চেক
$u_res = $conn->query("SELECT balance FROM users WHERE username = '$user'");
$u_data = $u_res->fetch_assoc();

if ($u_data['balance'] < $bet_amount) {
    echo json_encode(["status" => "error", "message" => "আপনার পর্যাপ্ত ব্যালেন্স নেই!"]);
    exit;
}

// ৪. বাজি ধরা: ব্যালেন্স কাটা এবং ৩টি টার্নওভার অটো আপডেট করা
$sql = "UPDATE users SET 
        balance = balance - $bet_amount, 
        turnover_completed = turnover_completed + $bet_amount, 
        bonus_t_done = bonus_t_done + $bet_amount, 
        pb_t_done = pb_t_done + $bet_amount 
        WHERE username = '$user'";

if ($conn->query($sql)) {
    // এখানে বাজি ধরার রেকর্ড একটি আলাদা টেবিলে রাখতে পারেন (ঐচ্ছিক)
    $conn->query("INSERT INTO bets (username, amount, game_id, status) VALUES ('$user', '$bet_amount', '$game_id', 'pending')");

    echo json_encode([
        "status" => "success", 
        "message" => "বাজি সফলভাবে ধরা হয়েছে!", 
        "new_balance" => ($u_data['balance'] - $bet_amount)
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "সার্ভার এরর! আবার চেষ্টা করুন।"]);
}
?>
