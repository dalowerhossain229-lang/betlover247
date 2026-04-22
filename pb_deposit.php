<?php 
session_start();
include 'header.php'; 
include 'db.php'; 
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
?>

<div style="padding: 20px; text-align: center; color: white;">
    <h2 style="color:#ffdf1b;">🎁 PROMOTIONAL DEPOSIT (PB)</h2>
    <div style="background:rgba(0,255,136,0.1); border:1px solid #00ff88; padding:15px; border-radius:12px; margin-bottom:20px;">
        <p style="margin:0; font-weight:bold; color:#00ff88;">৳১০০ থেকে ৳১০,০০০ পর্যন্ত ডিপোজিট করুন</p>
        <small style="color:#aaa;">অ্যাডমিন আপনার ডিপোজিটের ওপর প্রমোশনাল বোনাস ও টার্নওভার সেট করে দেবে।</small>
    </div>

    <div style="background:#111; padding:20px; border-radius:15px; border:1px solid #333; text-align: left;">
        <label style="color:#888; font-size:12px;">ডিপোজিট পরিমাণ (১০০ - ১০,০০০):</label>
        <input type="number" id="pb_amount" placeholder="৳ পরিমাণ লিখুন" style="width:100%; padding:15px; background:#000; border:1px solid #444; color:white; border-radius:10px; margin-top:8px; outline:none;">

        <label style="color:#888; font-size:12px; display:block; margin-top:20px;">পেমেন্ট মেথড:</label>
        <select id="pb_method" style="width:100%; padding:15px; background:#000; color:white; border:1px solid #444; border-radius:10px; margin-top:8px; outline:none;">
            <option value="Bkash Personal">বিকাশ পারসোনাল</option>
            <option value="Nagad Personal">নগদ পারসোনাল</option>
        </select>

        <label style="color:#888; font-size:12px; display:block; margin-top:20px;">ট্রানজেকশন আইডি (TrxID):</label>
        <input type="text" id="pb_trx" placeholder="TrxID দিন" style="width:100%; padding:15px; background:#000; border:1px solid #444; color:white; border-radius:10px; margin-top:8px; outline:none;">
        
        <button onclick="submitPB()" id="pbBtn" style="width:100%; padding:16px; background:#00ff88; color:#000; border:none; border-radius:10px; font-weight:bold; margin-top:25px; cursor:pointer;">রিকোয়েস্ট পাঠান</button>
    </div>
</div>

<script>
function submitPB() {
    const amt = document.getElementById('pb_amount').value;
    const trx = document.getElementById('pb_trx').value;
    const method = document.getElementById('pb_method').value;
    const btn = document.getElementById('pbBtn');
    
    if(amt < 100 || amt > 10000) { alert("পরিমাণ ১০০ থেকে ১০,০০০ এর মধ্যে হতে হবে!"); return; }
    if(!trx) { alert("TrxID দিন!"); return; }

    btn.disabled = true; btn.innerText = "প্রসেসিং...";

    let fd = new FormData();
    fd.append('amount', amt);
    fd.append('trx_id', trx);
    fd.append('method', method);

    fetch('process_pb_deposit.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.href='history.php';
        else { btn.disabled = false; btn.innerText = "রিকোয়েস্ট পাঠান"; }
    });
}
</script>
<?php include 'footer.php'; ?>
