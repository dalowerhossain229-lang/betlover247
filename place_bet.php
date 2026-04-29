<?php
// ১. সেশন স্টার্ট করা (সবার আগে এটি থাকতে হবে)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

// ২. সেশন থেকে ইউজার চেক (স্মার্ট চেক)
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';

if (empty($u)) {
    // যদি সেশন না পায় তবে ডাটাবেস এরর না দিয়ে এটি পাঠাবে
    echo json_encode(["status" => "error", "message" => "সেশন পাওয়া যায়নি! দয়া করে পেজটি রিফ্রেশ দিন।"]);
    exit;
}

// ৩. এরপর আপনার বাকি কুয়েরি লজিক...


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
