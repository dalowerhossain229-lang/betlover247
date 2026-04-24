<?php
include 'db.php';
// স্লাইডার টেবিল তৈরি
$sql = "CREATE TABLE IF NOT EXISTS slider_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if($conn->query($sql)) {
    echo "<h1>✅ স্লাইডার ডাটাবেস রেডি!</h1>";
} else {
    echo "এরর: " . $conn->error;
}
?>
