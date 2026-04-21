<?php
include 'db.php';

$sql = "CREATE TABLE IF NOT EXISTS `deposits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(20) NOT NULL,
  `trx_id` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conn->query($sql) === TRUE) {
    echo "<h1>চমৎকার! deposits টেবিলটি সফলভাবে তৈরি হয়েছে।</h1>";
} else {
    echo "Error: " . $conn->error;
}
?>
