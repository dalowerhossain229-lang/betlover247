<?php
include 'db.php';

// ১. সেটিংস টেবিল তৈরি করা
$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notice TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if($conn->query($sql)) {
    // ২. যদি কোনো ডাটা না থাকে তবে একটি ডিফল্ট নোটিশ যোগ করা
    $check = $conn->query("SELECT * FROM settings WHERE id = 1");
    if($check->num_rows == 0) {
        $conn->query("INSERT INTO settings (id, notice) VALUES (1, 'আমাদের সাইটে আপনাকে স্বাগতম!')");
    }
    echo "<h1>✅ সেটিংস টেবিল ১০০০% রেডি!</h1>";
    echo "<p>এখন আপনি অ্যাডমিন প্যানেল থেকে নোটিশ আপডেট করতে পারবেন।</p>";
} else {
    echo "এরর: " . $conn->error;
}
?>
