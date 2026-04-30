<?php
session_start();
include 'db.php';

// ১. সেশন থেকে ইউজার চেক
$u = $_SESSION['username'] ?? $_SESSION['user_id'] ?? '';
$bkash = isset($_POST['bkash']) ? mysqli_real_escape_string($conn, $_POST['bkash']) : '';
$nagad = isset($_POST['nagad']) ? mysqli_real_escape_string($conn, $_POST['nagad']) : '';

if (!empty($u)) {
    // ২. আগে চেক করা যে নম্বর অলরেডি সেভ আছে কি না (সিকিউরিটি)
    $check = $conn->query("SELECT bkash_number, nagad_number FROM users WHERE username = '$u'");
    $row = $check->fetch_assoc();

    // যদি নম্বর খালি থাকে তবেই আপডেট হবে
    if (empty($row['bkash_number']) || empty($row['nagad_number'])) {
        $sql = "UPDATE users SET bkash_number = '$bkash', nagad_number = '$nagad' WHERE username = '$u'";
        if ($conn->query($sql)) {
            // সেভ সফল হলে প্রোফাইল পেজে ফেরত যাবে
            header("Location: profile.php");
            exit();
        } else {
            echo "এরর: " . $conn->error;
        }
    } else {
        // যদি অলরেডি সেভ থাকে তবে কোনো পরিবর্তন হবে না
        header("Location: profile.php");
        exit();
    }
} else {
    echo "সেশন পাওয়া যায়নি। দয়া করে আবার লগইন করুন।";
}
?>
