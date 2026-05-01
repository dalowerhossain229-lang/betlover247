<?php
include 'db.php';
// টেবিল না থাকলে তৈরি করবে, থাকলে কলামগুলো চেক করবে
$sql = "CREATE TABLE IF NOT EXISTS bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    bet_amount DECIMAL(10,2),
    win_amount DECIMAL(10,2) DEFAULT 0,
    balance_type VARCHAR(20),
    game_name VARCHAR(50),
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if($conn->query($sql)){
    echo "<h2>✅ Bet Table is Ready!</h2>";
} else {
    echo "❌ Error: " . $conn->error;
}
?>
