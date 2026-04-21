<?php
// ১. ডাটাবেস কানেকশন চেক করা
include 'db.php';

if (!$conn) {
    die("কানেকশন ফেল: " . mysqli_connect_error());
}

// ২. টেবিল তৈরির SQL
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

// ৩. কুয়েরি রান এবং এরর ডিবাগিং
if ($conn->query($sql) === TRUE) {
    echo "<h1 style='color:green; text-align:center;'>চমৎকার! deposits টেবিলটি সফলভাবে তৈরি হয়েছে।</h1>";
} else {
    // এখানে আসল এররটি দেখাবে
    echo "<h1 style='color:red; text-align:center;'>টেবিল তৈরি হয়নি!</h1>";
    echo "<p style='text-align:center;'>এরর মেসেজ: " . $conn->error . "</p>";
}
?>
