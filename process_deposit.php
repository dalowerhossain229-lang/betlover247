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
$amount = mysqli_real_escape_string($conn, $_POST['amount'] ?? '');
$trx_id = mysqli_real_escape_string($conn, $_POST['trx_id'] ?? '');
$method = mysqli_real_escape_string($conn, $_POST['method'] ?? 'Unknown');

// ২. ইনপুট ভ্যালিডেশন
if (empty($amount) || empty($trx_id) || $amount <= 0) {
    echo json_encode(["status" => "error", "message" => "সঠিক তথ্য প্রদান করুন!"]);
    exit;
}

// ৩. ট্রানজেকশন আইডি ডুপ্লিকেট চেক
$check = $conn->query("SELECT id FROM deposits WHERE trx_id = '$trx_id'");
if ($check && $check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "এই TrxID দিয়ে আগে আবেদন করা হয়েছে!"]);
    exit;
}

// ৪. ডাটাবেসে রিকোয়েস্ট সেভ করা
$sql = "INSERT INTO deposits (username, amount, trx_id, method, status) VALUES ('$user', '$amount', '$trx_id', '$method', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "আবেদন সফল! এডমিন চেক করে ব্যালেন্স যোগ করে দিবে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর: " . $conn->error]);
}
?>

