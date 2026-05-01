<?php
include 'db.php';

echo "<body style='background:#000; color:#fff; font-family:sans-serif; padding:20px;'>";
echo "<h2>🛠️ Affiliate Database Fixer</h2>";

// ১. চেক করা কলামটি আছে কি না, না থাকলে তৈরি করা
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'is_affiliate'");
if ($check->num_rows == 0) {
    $conn->query("ALTER TABLE users ADD COLUMN is_affiliate INT DEFAULT 0");
    echo "<p style='color:#00ff88;'>✅ 'is_affiliate' কলাম তৈরি করা হয়েছে।</p>";
} else {
    echo "<p style='color:#ffdf1b;'>ℹ️ 'is_affiliate' কলাম আগে থেকেই আছে।</p>";
}

// ২. রেফার কোড এবং ব্যালেন্স কলামগুলো নিশ্চিত করা
$cols = [
    'ref_code' => "VARCHAR(20) UNIQUE",
    'ref_by' => "VARCHAR(50)",
    'aff_balance' => "DECIMAL(15,2) DEFAULT 0.00",
    'player_loss_profit' => "DECIMAL(15,2) DEFAULT 0.00"
];

foreach ($cols as $name => $type) {
    $c = $conn->query("SHOW COLUMNS FROM users LIKE '$name'");
    if ($c->num_rows == 0) {
        $conn->query("ALTER TABLE users ADD COLUMN $name $type");
        echo "<p style='color:#00ff88;'>✅ কলাম তৈরি: $name</p>";
    }
}

// ৩. Roky123 ইউজারকে ফোর্সলি অ্যাফিলিয়েট মেম্বার বানানো (টেস্ট করার জন্য)
$target_user = 'Roky123'; // এখানে আপনার ইউজারনেম দিন
$conn->query("UPDATE users SET is_affiliate = 1, ref_code = 'ROKY777' WHERE username = '$target_user'");

echo "<h3 style='color:#00ff88;'>🚀 ডাটাবেস এখন ১০০% রেডি!</h3>";
echo "<p>এখন আপনার প্রোফাইল পেজে গিয়ে রিফ্রেশ দিন, বাটনটি দেখতে পাবেন।</p>";
echo "</body>";
?>
