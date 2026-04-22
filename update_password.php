<?php 
session_start();
include 'header.php'; 
include 'db.php'; 
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>

<div style="padding: 20px; text-align: center; color: white;">
    <h2 style="color:#00ff88;">CHANGE PASSWORD</h2>
    <div style="background:#111; padding:20px; border-radius:15px; border:1px solid #333; margin-top:20px; text-align: left;">
        <label style="color:#888; font-size:12px;">পুরাতন পাসওয়ার্ড:</label>
        <input type="password" id="old_pass" style="width:100%; padding:15px; background:#000; border:1px solid #444; color:white; border-radius:10px; margin-bottom:15px;">

        <label style="color:#888; font-size:12px;">নতুন পাসওয়ার্ড:</label>
        <input type="password" id="new_pass" style="width:100%; padding:15px; background:#000; border:1px solid #444; color:white; border-radius:10px; margin-bottom:20px;">
        
        <button onclick="changePass()" id="pBtn" style="width:100%; padding:16px; background:#00ff88; color:#000; border:none; border-radius:10px; font-weight:bold; cursor:pointer;">UPDATE PASSWORD</button>
    </div>
</div>

<script>
function changePass() {
    const oldP = document.getElementById('old_pass').value;
    const newP = document.getElementById('new_pass').value;
    if(!oldP || !newP) { alert("ঘরগুলো পূরণ করুন!"); return; }

    let fd = new FormData();
    fd.append('old_pass', oldP);
    fd.append('new_pass', newP);

    fetch('update_password_proc.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.href='profile.php';
    });
}
</script>
<?php include 'footer.php'; ?>
``` [২, ৩]

### ২. দ্বিতীয় ফাইল তৈরি করুন: `update_password_proc.php`
গিটহাবে এই ফাইলটিও তৈরি করুন। এটি ডাটাবেসে নতুন পাসওয়ার্ড সেভ করবে:

```php
<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$user = $_SESSION['user_id'];
$old = $_POST['old_pass'];
$new = $_POST['new_pass'];

// পুরাতন পাসওয়ার্ড চেক
$res = $conn->query("SELECT password FROM users WHERE username = '$user'");
$u_data = $res->fetch_assoc();

if ($old !== $u_data['password']) {
    echo json_encode(["status" => "error", "message" => "পুরাতন পাসওয়ার্ড ভুল!"]);
    exit;
}
