<?php
session_start();
include 'db.php';
$conn->set_charset("utf8mb4");

// ১. অ্যাডমিন লগইন কনফিগারেশন
$admin_user = "admin24"; 
$admin_pass = "pass24"; 

if (isset($_GET['logout'])) { session_destroy(); header("Location: admin.php"); exit; }

if (isset($_POST['login'])) {
    if ($_POST['user'] == $admin_user && $_POST['pass'] == $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php"); exit;
    } else { $error = "ভুল আইডি বা পাসওয়ার্ড!"; }
}

// লগইন না থাকলে শুধু লগইন ফর্ম দেখাবে
if (!isset($_SESSION['admin_logged_in'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body { background:#0a0a0a; color:#fff; font-family:sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .login-box { background:#111; padding:30px; border-radius:15px; border:1px solid #007355; width:90%; max-width:350px; text-align:center; }
        input { width:100%; padding:12px; margin:10px 0; background:#000; border:1px solid #333; color:#fff; border-radius:8px; box-sizing:border-box; outline:none; }
        button { width:100%; padding:12px; background:#ffdf1b; border:none; border-radius:8px; font-weight:bold; cursor:pointer; color:#000; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="color:#ffdf1b">Admin Login</h2>
        <?php if(isset($error)) echo "<p style='color:red; font-size:12px;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="user" placeholder="Admin ID" required>
            <input type="password" name="pass" placeholder="Password" required>
            <button type="submit" name="login">Login Dashboard</button>
        </form>
    </div>
</body>
</html>
<?php exit; }

// ২. লজিক সেকশন (Approve, Reject, Ban, Game Update)
if(isset($_GET['approve_id'])) {
    $id = $_GET['approve_id'];
    $type = $_GET['type'];
    
    // ১. ট্রানজ্যাকশন টেবিল থেকে ডাটা আনা (এখানে কলামের নাম user_id)
    $res = $conn->query("SELECT * FROM transactions WHERE id='$id'");
    $data = $res->fetch_assoc();
    $uName = $data['user_id']; // ট্রানজ্যাকশন টেবিলের user_id থেকে ইউজারের নাম নেওয়া
    $amt = $data['amount'];

    // ২. ব্যালেন্স আপডেট কুয়েরি (ইউজার টেবিলের username কলামের সাথে মিলানো)
    if($type == 'deposit') {
        // SET balance = balance + $amt (আপনার স্ক্রিনশটের balance কলাম)
        // WHERE username = '$uName' (আপনার ইউজার টেবিলের username কলাম)
        $sql = "UPDATE users SET balance = balance + $amt WHERE username = '$uName'";
    } else {
        $sql = "UPDATE users SET balance = balance - $amt WHERE username = '$uName'";
    }

    if($conn->query($sql)) {
        // ৩. ব্যালেন্স আপডেট সফল হলে স্ট্যাটাস পরিবর্তন হবে
        $conn->query("UPDATE transactions SET status='approved' WHERE id='$id'");
        echo "<script>alert('টাকা সফলভাবে ইউজারের একাউন্টে জমা হয়েছে!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('ভুল: ব্যালেন্স আপডেট হয়নি! ডাটাবেস এরর: " . $conn->error . "'); window.location='admin.php';</script>";
    }
    exit;
}
if(isset($_GET['reject_id'])) {
    $id = $_GET['reject_id'];
    $conn->query("UPDATE transactions SET status='rejected' WHERE id='$id'");
    header("Location: admin.php"); exit;
}

if(isset($_GET['ban_user'])) {
    $uId = $_GET['ban_user'];
    $conn->query("UPDATE users SET status='banned' WHERE username='$uId'");
    header("Location: admin.php"); exit;
}

if(isset($_POST['update_game'])) {
    $gName = $_POST['gName']; $gLink = $_POST['gLink'];
    $conn->query("INSERT INTO games (name, link) VALUES ('$gName', '$gLink') ON DUPLICATE KEY UPDATE link='$gLink'");
    header("Location: admin.php");
    exit();
}
// এজেন্ট নম্বর পরিবর্তনের লজিক
if(isset($_POST['update_numbers'])) {
    $bkash = mysqli_real_escape_string($conn, $_POST['bkash']);
    $nagad = mysqli_real_escape_string($conn, $_POST['nagad']);
    $conn->query("UPDATE site_settings SET bkash_no='$bkash', nagad_no='$nagad' WHERE id=1");
    header("Location: admin.php?msg=NumberUpdated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { background:#050505; color:#eee; font-family:sans-serif; margin:0; padding:15px; }
        .nav { display:flex; justify-content:space-between; align-items:center; background:#111; padding:10px 20px; border-radius:10px; border-bottom:2px solid #ffdf1b; margin-bottom:20px; }
        .card { background:#111; padding:15px; border-radius:12px; margin-bottom:20px; border:1px solid #222; overflow-x: auto; }
        h3 { color:#ffdf1b; margin-top:0; font-size:16px; border-left:4px solid #007355; padding-left:10px; }
        table { width:100%; border-collapse:collapse; margin-top:10px; font-size:11px; min-width: 450px; }
        th, td { padding:8px; border:1px solid #222; text-align:left; }
        th { background:#007355; color:#fff; }
        .btn-act { padding:5px 8px; border-radius:4px; text-decoration:none; font-size:10px; font-weight:bold; display:inline-block; }
        .btn-approve { background:#ffdf1b; color:#000; }
        .btn-reject { background:#f44336; color:#fff; margin-left:4px; }
        .btn-ban { color:#ff4d4d; text-decoration:none; font-size:11px; font-weight:bold; }
        .logout { background:#ff4d4d; color:#fff; padding:5px 10px; border-radius:5px; text-decoration:none; font-size:12px; }
    </style>
</head>
<body>

<div class="nav">
    <h2 style="margin:0; color:#ffdf1b; font-size:18px;">BETLOVER365</h2>
    <a href="?logout=true" class="logout">LOGOUT</a>
</div>

<!-- সেকশন ১: গেম ম্যানেজমেন্ট (API) -->
<div class="card">
    <h3>Game Management (API Links)</h3>
    <form method="POST" style="display:flex; flex-direction:column; gap:8px; margin-top:10px;">
        <input type="text" name="gName" placeholder="Game Name" style="padding:10px; background:#000; color:#fff; border:1px solid #333; border-radius:5px;" required>
        <input type="text" name="gLink" placeholder="API/Game URL" style="padding:10px; background:#000; color:#fff; border:1px solid #333; border-radius:5px;" required>
        <button type="submit" name="update_game" style="padding:10px; background:#007355; color:#fff; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">UPDATE GAME</button>
    </form>
</div>
<!-- অন্যান্য সেকশন শেষ হওয়ার পর -->

<div class="card">
    <h3>Agent Number Management</h3>
    <form method="POST" style="display:flex; flex-direction:column; gap:10px; margin-top:10px;">
        <input type="text" name="bkash" placeholder="Bkash Number" style="padding:10px; background:#000; color:#fff; border:1px solid #333;" required>
        <input type="text" name="nagad" placeholder="Nagad Number" style="padding:10px; background:#000; color:#fff; border:1px solid #333;" required>
        <button type="submit" name="update_numbers" style="padding:10px; background:#ffdf1b; color:#000; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">UPDATE NUMBERS</button>
    </form>
</div>

<!-- নিচের অন্যান্য টেবিল বা কন্টেন্ট শুরু -->

<!-- সেকশন ২: লেনদেন কন্ট্রোল (Approve/Reject) -->
<div class="card">
    <h3>Pending Transactions (Deposit/Withdraw)</h3>
    <table>
        <tr><th>User ID</th><th>Type</th><th>Amount</th><th>TrxID/Number</th><th>Action</th></tr>
        <?php
        $res = $conn->query("SELECT * FROM transactions WHERE status='pending' ORDER BY id DESC");
        while($row = $res->fetch_assoc()) {
            $details = ($row['type'] == 'deposit') ? $row['trx_id'] : $row['target_number'];
            echo "<tr>
                <td>{$row['user_id']}</td>
                <td style='color:#ffa500; font-weight:bold;'>" . strtoupper($row['type']) . "</td>
                <td>৳{$row['amount']}</td>
                <td>$details</td>
                <td>
                    <a href='?approve_id={$row['id']}&type={$row['type']}' class='btn-act btn-approve'>APPROVE</a>
                    <a href='?reject_id={$row['id']}' class='btn-act btn-reject' onclick='return confirm(\"বাতিল করবেন?\")'>REJECT</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>

<!-- সেকশন ৩: ইউজার ম্যানেজমেন্ট (Ban System) -->
<div class="card">
    <h3>Active Users List</h3>
    <table>
        <tr><th>Username</th><th>Balance</th><th>Status</th><th>Action</th></tr>
        <?php
        $uRes = $conn->query("SELECT username, balance, status FROM users WHERE status != 'admin' ORDER BY id DESC");
        while($uRow = $uRes->fetch_assoc()) {
            echo "<tr>
                <td>{$uRow['username']}</td>
                <td>৳{$uRow['balance']}</td>
                <td>{$uRow['status']}</td>
                <td><a href='?ban_user={$uRow['username']}' class='btn-ban' onclick='return confirm(\"ব্যান নিশ্চিত?\")'>BAN USER</a></td>
            </tr>";
        }
        ?>
    </table>
</div>

<!-- সেকশন ৪: প্রোমোশন রিকোয়েস্ট (৫টি পয়েন্ট) -->
<div class="card">
    <h3>Promotion & Investment Requests</h3>
    <table>
        <tr><th>User</th><th>Category</th><th>Information</th></tr>
        <?php
        $pRes = $conn->query("SELECT * FROM promo_applications ORDER BY id DESC");
        while($pRow = $pRes->fetch_assoc()) {
            echo "<tr>
                <td>{$pRow['user_id']}</td>
                <td><b style='color:#ffdf1b;'>{$pRow['promo_type']}</b></td>
                <td style='font-size:10px;'>{$pRow['details']}</td>
            </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
