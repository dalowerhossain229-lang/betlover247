<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) { exit; }
$user = $_SESSION['user_id'];

// বর্তমান ডাটা চেক করা (আগে সেভ করা আছে কি না)
$check = $conn->query("SELECT p_bkash, p_nagad FROM users WHERE username = '$user'")->fetch_assoc();

$bkash = mysqli_real_escape_string($conn, $_POST['bkash']);
$nagad = mysqli_real_escape_string($conn, $_POST['nagad']);

// শুধু খালি ঘরগুলোতেই নতুন নম্বর সেভ হবে (লক লজিক)
$sql = "UPDATE users SET ";
$updates = [];
if(empty($check['p_bkash']) && !empty($bkash)) $updates[] = "p_bkash = '$bkash'";
if(empty($check['p_nagad']) && !empty($nagad)) $updates[] = "p_nagad = '$nagad'";

if(count($updates) > 0) {
    $sql .= implode(', ', $updates) . " WHERE username = '$user'";
    if($conn->query($sql)) {
        echo json_encode(["status" => "success", "message" => "নম্বরগুলো চিরস্থায়ীভাবে সেভ হয়েছে!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "এরর: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "আপনি ইতিমধ্যে নম্বর সেভ করেছেন!"]);
}
?>
