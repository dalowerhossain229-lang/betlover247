<?php
include 'db.php';
// settings টেবিলে game_logic কলাম যোগ করা (যদি না থাকে)
$conn->query("ALTER TABLE settings ADD COLUMN IF NOT EXISTS game_logic VARCHAR(20) DEFAULT 'random'");
// ডিফল্ট মান সেট করা
$conn->query("UPDATE settings SET game_logic = 'random' WHERE id = 1");
echo "Game Logic System Installed! Set logic to 'win', 'loss', or 'random' in DB.";
?>
