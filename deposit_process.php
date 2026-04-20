<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "দয়া করে লগইন করুন!"]);
    exit;
}

$uid = $_SESSION['user_id'];
$amount = $_POST['amount'] ?? 0;
$trx = mysqli_real_escape_string($conn, $_POST['trx_id'] ?? '');

// একই TrxID আগে ব্যবহার হয়েছে কি না চেক
$check = $conn->query("SELECT id FROM deposits WHERE trx_id = '$trx'");

if($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই TrxID টি ইতিমধ্যে ব্যবহৃত হয়েছে!"]);
} else {
    $sql = "INSERT INTO deposits (user_id, amount, method, trx_id, status) VALUES ('$uid', '$amount', 'Bkash/Nagad', '$trx', 'pending')";
    if($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "রিকোয়েস্ট পাঠানো হয়েছে! অ্যাডমিন চেক করে এপ্রুভ করবে।"]);
    } else {
        echo json_encode(["status" => "error", "message" => "সার্ভার এরর!"]);
    }
}
?>
