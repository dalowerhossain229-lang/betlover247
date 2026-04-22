<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "সেশন আউট! আবার লগইন করুন।"]);
    exit;
}

$user = $_SESSION['user_id'];
$amount = intval($_POST['amount'] ?? 0);
$method = mysqli_real_escape_string($conn, $_POST['method'] ?? '');

// ২. ইউজারের তথ্য ও টার্নওভার ডাটাবেস থেকে নেওয়া
$res = $conn->query("SELECT balance, turnover_target, turnover_completed FROM users WHERE username = '$user'");
$u_data = $res->fetch_assoc();

// ৩. টার্নওভার চেক
if ($u_data['turnover_completed'] < $u_data['turnover_target']) {
    echo json_encode(["status" => "error", "message" => "উইথড্র দিতে আগে আপনার টার্নওভার টার্গেট সম্পন্ন করুন!"]);
    exit;
}

// ৪. উইথড্র লিমিট চেক (১০০ - ২৫,০০০)
if ($amount < 100 || $amount > 25000) {
    echo json_encode(["status" => "error", "message" => "উইথড্র লিমিট ১০০ থেকে ২৫,০০০ টাকা!"]);
    exit;
}

// ৫. পর্যাপ্ত ব্যালেন্স চেক
if ($amount > $u_data['balance']) {
    echo json_encode(["status" => "error", "message" => "আপনার পর্যাপ্ত ব্যালেন্স নেই!"]);
    exit;
}

// ৬. ব্যালেন্স কাটা এবং রিকোয়েস্ট সেভ করা
$conn->query("UPDATE users SET balance = balance - $amount WHERE username = '$user'");
$sql = "INSERT INTO withdraws (username, amount, method, status) VALUES ('$user', '$amount', '$method', 'pending')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "উইথড্র রিকোয়েস্ট সফল! এডমিন চেক করে টাকা পাঠিয়ে দিবে।"]);
} else {
    // এরর হলে কাটা টাকা ফেরত দেওয়া (নিরাপত্তার জন্য)
    $conn->query("UPDATE users SET balance = balance + $amount WHERE username = '$user'");
    echo json_encode(["status" => "error", "message" => "ডাটাবেস এরর! আবার চেষ্টা করুন।"]);
}
?>
