<?php 
session_start();
include 'header.php'; 
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
?>

<div style="padding: 20px; text-align: center; color: white; font-family: sans-serif; min-height: 80vh;">
    <h2 style="color:#00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.3);">🔐 CHANGE PASSWORD</h2>
    
    <div style="background: #111; padding: 25px; border-radius: 15px; border: 1px solid #333; text-align: left; max-width: 400px; margin: 20px auto;">
        <label style="color:#888; font-size: 12px;">পুরাতন পাসওয়ার্ড:</label>
        <input type="password" id="old_p" placeholder="Old Password" style="width: 100%; padding: 15px; background: #000; border: 1px solid #444; color: white; border-radius: 10px; margin-top: 10px; outline: none; box-sizing: border-box;">

        <label style="color:#888; font-size: 12px; display: block; margin-top: 20px;">নতুন পাসওয়ার্ড:</label>
        <input type="password" id="new_p" placeholder="New Password" style="width: 100%; padding: 15px; background: #000; border: 1px solid #444; color: white; border-radius: 10px; margin-top: 10px; outline: none; box-sizing: border-box;">
        
        <button onclick="changePass()" id="pBtn" style="width: 100%; padding: 16px; background: #00ff88; color: #000; border: none; border-radius: 10px; font-weight: bold; margin-top: 30px; cursor: pointer; font-size: 16px;">পাসওয়ার্ড আপডেট করুন</button>
    </div>
</div>

<script>
function changePass() {
    const oldP = document.getElementById('old_p').value;
    const newP = document.getElementById('new_p').value;
    const btn = document.getElementById('pBtn');

    if(!oldP || !newP) { alert("সবগুলো ঘর পূরণ করুন!"); return; }
    
    btn.disabled = true; btn.innerText = "প্রসেসিং...";

    let fd = new FormData();
    fd.append('old_pass', oldP);
    fd.append('new_pass', newP);

    fetch('update_password_proc.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.href = 'profile.php';
        else { btn.disabled = false; btn.innerText = "পাসওয়ার্ড আপডেট করুন"; }
    })
    .catch(() => { alert("সার্ভার এরর!"); btn.disabled = false; });
}
</script>
<?php include 'footer.php'; ?>
