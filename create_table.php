<?php
include 'db.php';

// ১. টেবিল তৈরির SQL কোড
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

// ২. কোডটি রান করা এবং রেজাল্ট দেখানো
if ($conn->query($sql) === TRUE) {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h1 style='color:#00ff88;'>চমৎকার!</h1>
            <p style='font-size:20px;'>deposits টেবিলটি সফলভাবে আপনার ডাটাবেসে তৈরি হয়েছে।</p>
            <p style='color:red;'><b>নিরাপত্তার জন্য এখনই গিটহাব থেকে create_table.php ফাইলটি ডিলিট করে দিন।</b></p>
          </div>";
} else {
    echo "Error: " . $conn->error;
}
?>
