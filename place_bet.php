<?php
ob_start();
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
$amount = 10;
$wallet = $_POST['wallet'] ?? 'main';

if (empty($u)) {
    die("error");
}

// ২. ওয়ালেট কলাম নির্ধারণ (আপনার ডাটাবেসের কলামের নামগুলো নিশ্চিত করুন)
if ($wallet == 'pb') { 
    $col = "pb_balance"; $t_col = "pb_t"; 
} elseif ($wallet == 'bonus') { 
    $col = "bonus_balance"; $t_col = "bonus_t"; 
} else { 
    $col = "balance"; $t_col = "main_t"; 
}

// ৩. ডাটাবেস আপডেট (আমরা শুধু ইউজারনেম দিয়ে চেক করছি যাতে ভুল না হয়)
$sql = "UPDATE users SET $col = $col - $amount, $t_col = $t_col + $amount WHERE username = '$u'";

if ($conn->query($sql)) {
    // ডাটাবেসে আপডেট হয়েছে কি না তা চেক করা
    if ($conn->affected_rows > 0) {
        $conn->query("INSERT INTO game_history (username, game_name, wallet_type, bet_amount) VALUES ('$u', '2048 Game', '$wallet', $amount)");
        echo "success";
    } else {
        // যদি ইউজারনেম না মিলে তবে আইডি দিয়ে চেষ্টা করবে
        $conn->query("UPDATE users SET $col = $col - $amount, $t_col = $t_col + $amount WHERE id = '$u'");
        echo "success";
    }
} else {
    echo "error";
}
ob_end_flush();
?>
