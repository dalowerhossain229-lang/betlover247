<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$user = $_SESSION['user_id'];
$amount = intval($_POST['amount']);
$method = mysqli_real_escape_string($conn, $_POST['method']);

// ১. ডাটা চেক
$res = $conn->query("SELECT balance, turnover_target, turnover_completed FROM users WHERE username = '$user'");
$u_data = $res->fetch_assoc();

// ২. টার্নওভার চেক (লজিক ফিক্স)
if ($u_data['turnover_completed'] < $u_data['turnover_target']) {
    echo json_encode(["status" => "error", "message" => "উইথড্র দিতে আগে টার্নওভার সম্পন্ন করুন!"]);
    exit;
}

// ৩. লিমিট চেক
if ($amount < 100 || $amount > 25000) {
    echo json_encode(["status" => "error", "message" => "উইথড্র লিমিট ১০০ থেকে ২৫,০০০ টাকা!"]);
    exit;
}

// ৪. ব্যালেন্স চেক
if ($amount > $u_data['balance']) {
    echo json_encode(["status" => "error", "message" => "আপনার পর্যাপ্ত ব্যালেন্স নেই!"]);
    exit;
}

// ৫. ট্রানজেকশন সেভ ও ব্যালেন্স কাটা
$conn->query("UPDATE users SET balance = balance - $amount WHERE username = '$user'");
$sql = "INSERT INTO withdraws (username, amount, method, status) VALUES ('$user', '$amount', '$method', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "উইথড্র রিকোয়েস্ট পেন্ডিং। এডমিন চেক করে টাকা পাঠিয়ে দিবে।"]);
} else {
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর!"]);
}
?>
