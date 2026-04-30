<?php
session_start();
include 'db.php';

// নিরাপত্তা চেক (ঐচ্ছিক: শুধু আপনি দেখতে চাইলে আপনার ইউজারনেম দিতে পারেন)
echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2 style='color:#00ff88; text-align:center;'>🔑 User Password Inspector</h2>";

// ডাটাবেস থেকে সব ইউজারের তথ্য আনা
$query = $conn->query("SELECT id, username, password, balance FROM users ORDER BY id DESC");

if ($query->num_rows > 0) {
    echo "<table border='1' style='width:100%; border-collapse:collapse; background:#111; border:1px solid #333; text-align:left;'>";
    echo "<tr style='background:#222; color:#ffdf1b;'>
            <th style='padding:12px;'>ID</th>
            <th style='padding:12px;'>Username</th>
            <th style='padding:12px;'>Password</th>
            <th style='padding:12px;'>Balance</th>
          </tr>";

    while ($row = $query->fetch_assoc()) {
        echo "<tr style='border-bottom:1px solid #333;'>";
        echo "<td style='padding:10px;'>" . $row['id'] . "</td>";
        echo "<td style='padding:10px; color:#00ff88; font-weight:bold;'>" . $row['username'] . "</td>";
        echo "<td style='padding:10px; color:#ff4d4d; font-weight:bold;'>" . $row['password'] . "</td>";
        echo "<td style='padding:10px;'>৳" . number_format($row['balance'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red; text-align:center;'>❌ ডাটাবেসে কোনো ইউজার পাওয়া যায়নি।</p>";
}

echo "<br><p style='text-align:center; color:#888;'>কাজ শেষ হলে ফাইলটি ডিলিট করে দিতে ভুলবেন না।</p>";
echo "</body>";
?>
