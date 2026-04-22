<?php
include 'db.php';
// ১. turnover কলামগুলো যোগ করা (সব ভার্সনের জন্য নিরাপদ কোড)
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'turnover_completed'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD turnover_target INT DEFAULT 1000");
    $conn->query("ALTER TABLE users ADD turnover_completed INT DEFAULT 0");
    echo "<h1>✅ ডাটাবেস আপডেট সফল!</h1>";
} else {
    echo "<h1>ℹ️ এটি আগেই আপডেট করা আছে।</h1>";
}
?>
