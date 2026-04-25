<?php
include 'db.php';
$sql = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
$conn->query($sql);

// যদি কোনো অ্যাডমিন না থাকে তবে একটি ডিফল্ট আইডি পাসওয়ার্ড যোগ করা
$check = $conn->query("SELECT * FROM admin");
if($check->num_rows == 0){
    $pass = password_hash("admin123", PASSWORD_DEFAULT); // পাসওয়ার্ড: admin123
    $conn->query("INSERT INTO admin (username, password) VALUES ('admin', '$pass')");
}
echo "<h1>✅ Admin Table Ready! ID: admin, Pass: admin123</h1>";
?>
