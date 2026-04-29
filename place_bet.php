<?php
session_start();
include 'db.php';

// ১. সেশন চেক
$u = $_SESSION['username'] ?? '';
$amount = 10; // টেস্টের জন্য ১০ টাকা
$wallet = $_POST['wallet'] ?? 'main';

if (empty($u)) {
    echo json_encode(["status" => "error", "message" => "সেশন পাওয়া যায়নি!"]);
    exit;
}

// ২. কলাম নির্ধারণ
if ($wallet == 'pb') { 
    $col = "pb_balance"; $t_col = "pb_t"; 
} elseif ($wallet == 'bonus') { 
    $col = "bonus_balance"; $t_col = "bonus_t"; 
} else { 
    $col = "balance"; $t_col = "main_t"; 
}

// ৩. ডাটাবেসে টাকা কাটা এবং টার্নওভার বাড়ানো
$sql = "UPDATE users SET $col = $col - $amount, $t_col = $t_col + $amount WHERE username = '$u'";

if ($conn->query($sql)) {
    // ৪. গেম হিস্টোরিতে রেকর্ড রাখা (যদি টেবিল থাকে)
    $conn->query("INSERT INTO game_history (username, game_name, wallet_type, bet_amount) VALUES ('$u', '2048 Game', '$wallet', $amount)");
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>
