<?php
include 'db.php';

// ১. ইউজার টেবিলে turnover কলাম যোগ করা
$sql = "ALTER TABLE users ADD COLUMN turnover DECIMAL(10, 2) DEFAULT 0.00";

if($conn->query($sql)) {
    echo "<h1>✅ Turnover System Fixed!</h1>";
    echo "<p>এখন আপনি গেম খেললে টাকাও কাটবে এবং টার্নওভারও বাড়বে।</p>";
} else {
    echo "এরর: আপনার ডাটাবেসে হয়তো এটি আগে থেকেই আছে অথবা: " . $conn->error;
}
?>
