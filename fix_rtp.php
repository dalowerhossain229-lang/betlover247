<?php
include 'db.php';

// ১. সেটিংস টেবিলে rtp_value কলামটি যোগ করা
$sql = "ALTER TABLE settings ADD COLUMN IF NOT EXISTS rtp_value INT DEFAULT 50";

if($conn->query($sql)) {
    // ২. ডিফল্টভাবে ৫০% উইন চান্স সেট করা
    $conn->query("UPDATE settings SET rtp_value = 50 WHERE id = 1");
    echo "<h1>✅ RTP System Fixed!</h1>";
    echo "<p>এখন আপনি Ludu গেমটি ওপেন করতে পারবেন।</p>";
} else {
    echo "এরর: " . $conn->error;
}
?>
