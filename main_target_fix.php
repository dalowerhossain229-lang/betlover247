<?php
include 'db.php';

echo "<h2>🛠️ Synchronizing Deposit as Target...</h2>";

// ১. ইউজারের মোট সফল ডিপোজিট বের করা (কোনো ফিক্সড ১০০০ ছাড়াই)
$users = $conn->query("SELECT username FROM users");

while($u = $users->fetch_assoc()) {
    $uname = $u['username'];
    
    // ইউজারের সফল ডিপোজিট চেক (status success বা Approved যাই হোক)
    $dep = $conn->query("SELECT SUM(amount) as total FROM deposits WHERE username = '$uname' AND (status = 'success' OR status = 'Approved')")->fetch_assoc();
    
    // ইউজার যদি ১০০ টাকা করে তবে ১০০, ১ টাকা করলে ১—কোনো ফিক্সড লিমিট নেই
    $total_dep = (float)($dep['total'] ?? 0);

    // ডাটাবেসের settings টেবিলে এই মানটি আপডেট করা
    $conn->query("UPDATE settings SET main_target = $total_dep WHERE id = 1");
    
    echo "✅ <b>$uname</b> এর জন্য নতুন টার্গেট সেট হয়েছে: ৳ $total_dep<br>";
}

echo "<h3>🚀 Success! প্রোফাইল চেক করুন।</h3>";
?>
