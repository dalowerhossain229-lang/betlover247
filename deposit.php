<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

// ডাটাবেস থেকে ৪টি নম্বর নিয়ে আসা
$configs = $conn->query("SELECT * FROM site_configs");
$nums = [];
if($configs) {
    while($r = $configs->fetch_assoc()){ 
        $nums[$r['config_key']] = $r['config_value']; 
    }
}
?>

<div style="padding: 20px; text-align: center; color: white; font-family: sans-serif;">
    <h2 style="color:#00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.5);">💳 SELECT METHOD</h2>
    <p style="font-size: 12px; color: #888; margin-bottom: 20px;">টাকা পাঠিয়ে নিচের তথ্যগুলো পূরণ করুন</p>
    
    <!-- ৪টি মেথড বাটন -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 25px;">
        <div onclick="selectMethod('Bkash Personal', '<?php echo $nums['bkash_per'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:15px; border-radius:12px; border:1px solid #d12053; cursor:pointer; transition: 0.3s;">
            <img src="https://betlover777.com" width="35" onerror="this.src='https://placeholder.com'"><br><small style="display:block; margin-top:5px;">বিকাশ পারসোনাল</small>
        </div>
        <div onclick="selectMethod('Bkash Agent', '<?php echo $nums['bkash_agt'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:15px; border-radius:12px; border:1px solid #d12053; cursor:pointer;">
            <img src="https://betlover777.com" width="35" onerror="this.src='https://placeholder.com'"><br><small style="display:block; margin-top:5px;">বিকাশ এজেন্ট</small>
        </div>
        <div onclick="selectMethod('Nagad Personal', '<?php echo $nums['nagad_per'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:15px; border-radius:12px; border:1px solid #f7941d; cursor:pointer;">
            <img src="https://betlover777.com" width="35" onerror="this.src='https://placeholder.com'"><br><small style="display:block; margin-top:5px;">নগদ পারসোনাল</small>
        </div>
        <div onclick="selectMethod('Nagad Agent', '<?php echo $nums['nagad_agt'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:15px; border-radius:12px; border:1px solid #f7941d; cursor:pointer;">
            <img src="https://betlover777.com" width="35" onerror="this.src='https://placeholder.com'"><br><small style="display:block; margin-top:5px;">নগদ এজেন্ট</small>
        </div>
    </div>

    <!-- নম্বর ডিসপ্লে বক্স -->
    <div id="numberBox" style="display:none; background:rgba(0,255,136,0.05); padding:20px; border-radius:15px; border:1px dashed #00ff88; margin-bottom:25px; animation: fadeIn 0.5s;">
        <p id="methodName" style="color:#00ff88; font-size:13px; font-weight:bold; margin:0;"></p>
        <h2 id="displayNum" style="color:#ffdf1b; margin:12px 0; font-size:28px; letter-spacing: 1px;"></h2>
        <button onclick="copyNum()" style="background:#00ff88; color:#000; border:none; padding:8px 25px; border-radius:8px; font-weight:900; cursor:pointer; font-size:12px;">COPY NUMBER</button>
    </div>

    <!-- ইনপুট ফর্ম -->
    <div id="depositForm" style="display:none;">
        <input type="number" id="d_amount" placeholder="কত টাকা পাঠিয়েছেন?" style="width:100%; padding:15px; background:#111; border:1px solid #333; color:white; border-radius:10px; margin-bottom:15px; box-sizing: border-box; outline: none;">
        <input type="text" id="d_trx" placeholder="Transaction ID (TrxID)" style="width:100%; padding:15px; background:#111; border:1px solid #333; color:white; border-radius:10px; margin-bottom:20px; box-sizing: border-box; outline: none;">
        <button onclick="submitDeposit()" id="subBtn" style="width:100%; padding:16px; background:#ffdf1b; border:none; border-radius:10px; font-weight:900; color:#000; cursor:pointer; font-size:16px; transition: 0.3s;">রিকোয়েস্ট পাঠান</button>
    </div>
    
    <p onclick="location.href='index.php'" style="color:#555; margin-top:20px; cursor:pointer; font-size:14px;">ফিরে যান</p>
</div>

<style> @keyframes fadeIn { from {opacity:0; transform: translateY(-10px);} to {opacity:1; transform: translateY(0);} } </style>

<script>
let selectedMethod = "";
function selectMethod(m, num) {
    selectedMethod = m;
    document.getElementById('methodName').innerText = m + " - Cash Out";
    document.getElementById('displayNum').innerText = num;
    document.getElementById('numberBox').style.display = 'block';
    document.getElementById('depositForm').style.display = 'block';
}

function copyNum() {
    let num = document.getElementById('displayNum').innerText;
    navigator.clipboard.writeText(num);
    alert("নম্বর কপি হয়েছে: " + num);
}

function submitDeposit() {
    const amount = document.getElementById('d_amount').value;
    const trx = document.getElementById('d_trx').value;
    const btn = document.getElementById('subBtn');

    if(!amount || !trx) { alert("টাকা এবং TrxID দিন!"); return; }
    
    btn.disabled = true;
    btn.innerText = "প্রসেসিং হচ্ছে...";

    let fd = new FormData();
    fd.append('amount', amount);
    fd.append('trx_id', trx);
    fd.append('method', selectedMethod);

    fetch('process_deposit.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.href='index.php';
        else { btn.disabled = false; btn.innerText = "রিকোয়েস্ট পাঠান"; }
    })
    .catch(() => { alert("সার্ভার কানেকশন এরর!"); btn.disabled = false; btn.innerText = "রিকোয়েস্ট পাঠান"; });
}
</script>
<?php include 'footer.php'; ?>
