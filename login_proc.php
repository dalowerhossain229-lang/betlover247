<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$user = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

// ডাটাবেস থেকে ইউজার চেক
$res = $conn->query("SELECT * FROM users WHERE username = '$user'");

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    
    // পাসওয়ার্ড যাচাই
    if (password_verify($pass, $row['password'])) {
        // সেশনে তথ্য সেভ করা (এটিই ব্যালেন্স স্থায়ী করবে)
        $_SESSION['user_id'] = $row['username'];
        $_SESSION['balance'] = (float)$row['balance']; 
        
        // সেশনটি সার্ভারে সেভ হওয়া নিশ্চিত করা
        session_write_close(); 
        
        echo json_encode(["status" => "success", "message" => "লগইন সফল!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ভুল পাসওয়ার্ড!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ইউজার আইডি পাওয়া যায়নি!"]);
}
?>
