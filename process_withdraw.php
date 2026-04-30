<?php
ob_start();
session_start();
include 'db.php';

$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$method = isset($_POST['method']) ? mysqli_real_escape_string($conn, $_POST['method']) : '';

if (empty($u) || $amount < 100 || $amount > 25000) {
    die("❌ সঠিক তথ্য দিন। সর্বনিম্ন ১০০ এবং সর্বোচ্চ ২৫,০০০ টাকা উইথড্র করা যাবে।");
}

// ইউজারের লেটেস্ট তথ্য চেক করা
$res = $conn->query("SELECT * FROM users WHERE username = '$u'");
$u_data = $res->fetch_assoc();

// ১. অটো-ক্যানসেল লজিক: টার্নওভার পূরণ না হলে রিকোয়েস্ট ডাটাবেসে যাবেই না
if ($u_data['main_t'] < $u_data['t_main']) {
    die("<body style='background:#000; color:#fff; text-align:center; padding:50px; font-family:sans-serif;'>
            <div style='border:1px solid #ff4d4d; padding:30px; border-radius:15px; display:inline-block;'>
                <h2 style='color:#ff4d4d;'>❌ উইথড্র বাতিল!</h2>
                <p>আপনার টার্নওভার টার্গেট এখনো পূরণ হয়নি।</p>
                <p style='color:#888;'>টার্গেট: ৳".$u_data['t_main']." | আপনার খেলা: ৳".$u_data['main_t']."</p>
                <br><a href='play.php' style='background:#ff4d4d; color:#fff; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>বাজি খেলে প্রগ্রেস শেষ করুন</a>
            </div></body>");
}

// ২. ব্যালেন্স চেক
if ($u_data['balance'] < $amount) {
    die("❌ আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালেন্স নেই।");
}

// ৩. নম্বর চেক (প্রোফাইল থেকে নম্বর তুলে নেওয়া)
$target_number = ($method == 'bkash') ? $u_data['bkash_number'] : $u_data['nagad_number'];
if (empty($target_number)) {
    die("❌ আপনার এই মেথডে কোনো নম্বর সেভ করা নেই। প্রোফাইলে গিয়ে নম্বর সেট করুন।");
}

// ৪. সাকসেস প্রসেস: টাকা কাটা এবং রিকোয়েস্ট সেভ
$new_balance = $u_data['balance'] - $amount;
$conn->query("UPDATE users SET balance = $new_balance WHERE username = '$u'");

// অ্যাডমিন প্যানেলের জন্য রিকোয়েস্ট টেবিল (নিশ্চিত করুন withdraw_requests টেবিলটি আছে)
$request_sql = "INSERT INTO withdraw_requests (username, amount, method, number, status, date) 
                VALUES ('$u', '$amount', '$method', '$target_number', 'Pending', NOW())";

if ($conn->query($request_sql)) {
    echo "<body style='background:#000; color:#fff; text-align:center; padding:50px; font-family:sans-serif;'>
            <div style='border:1px solid #00ff88; padding:30px; border-radius:15px; display:inline-block;'>
                <h2 style='color:#00ff88;'>✅ রিকোয়েস্ট সফল!</h2>
                <p>আপনার ৳$amount উইথড্র রিকোয়েস্ট পেন্ডিং আছে।</p>
                <p style='color:#888;'>বর্তমান ব্যালেন্স: ৳$new_balance</p>
                <br><a href='index.php' style='background:#00ff88; color:#000; padding:10px 20px; border-radius:5px; text-decoration:none; font-weight:bold;'>হোমে ফিরে যান</a>
            </div></body>";
} else {
    echo "এরর: " . $conn->error;
}
ob_end_flush();
?>
