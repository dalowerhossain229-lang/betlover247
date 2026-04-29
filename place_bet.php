<?php
session_start();
include 'db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
// নিশ্চিত করুন db.php ফাইলটি এই একই ফোল্ডারে আছে

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
$bet = isset($_POST['amount']) ? (float)$_POST['amount'] : 10;
$wallet = isset($_POST['wallet']) ? $_POST['wallet'] : 'main';

if (empty($u)) {
    echo json_encode(["status" => "error", "message" => "দয়া করে আগে লগইন করুন!"]);
    exit;
}

// ২. সিলেক্ট করা ওয়ালেট অনুযায়ী ডাটাবেস কলাম ঠিক করা
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

// ৩. ডাটাবেস থেকে চেক করা যে সেই ওয়ালেটে পর্যাপ্ত টাকা আছে কি না
$check = $conn->query("SELECT $bal_col FROM users WHERE username = '$u' OR id = '$u'");
$user_data = $check->fetch_assoc();

if (!$user_data || $user_data[$bal_col] < $bet) {
    echo json_encode(["status" => "error", "message" => "আপনার " . strtoupper($wallet) . " ওয়ালেটে পর্যাপ্ত ব্যালেন্স নেই!"]);
    exit;
}

// ৪. শুধুমাত্র সিলেক্ট করা ওয়ালেট থেকে টাকা কাটা এবং সেই টার্নওভার বাড়ানো
$sql = "UPDATE users SET $bal_col = $bal_col - $bet, $turn_col = $turn_col + $bet WHERE username = '$u' OR id = '$u'";

if ($conn->query($sql)) {
    // ৫. হিস্টোরিতে রেকর্ড সেভ করা
    $conn->query("INSERT INTO game_history (username, game_name, wallet_type, bet_amount) VALUES ('$u', 'Testing Game', '$wallet', $bet)");
    
    echo json_encode(["status" => "success", "message" => "বাজি সফল! " . strtoupper($wallet) . " ব্যালেন্স থেকে " . $bet . " টাকা কাটা হয়েছে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
