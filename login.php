<?php
include 'db.php';
session_start();
header('Content-Type: application/json');

$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

if (empty($user) || empty($pass)) {
    echo json_encode(["status" => "error", "message" => "আইডি ও পাসওয়ার্ড দিন!"]);
    exit;
}

// ডাটাবেস থেকে ইউজার চেক করা
$res = $conn->query("SELECT * FROM users WHERE username = '$user'");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    
    // পাসওয়ার্ড যাচাই করা
    if (password_verify($pass, $row['password'])) {
        // ইউজার কি ব্যান করা?
        if ($row['status'] == 'banned') {
            echo json_encode(["status" => "error", "message" => "দুঃখিত, আপনার একাউন্টটি নিষিদ্ধ (Banned) করা হয়েছে!"]);
        } else {
            // সেশনে তথ্য সেভ করা (খুবই জরুরি)
            $_SESSION['user_id'] = $row['username'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['balance'] = $row['balance']; // এটি মেইন সাইটে ব্যালেন্স দেখাবে
            
            echo json_encode(["status" => "success", "message" => "লগইন সফল!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ভুল পাসওয়ার্ড!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ইউজার আইডি পাওয়া যায়নি!"]);
}
?>
