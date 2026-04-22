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

<div style="padding: 20px; text-align: center; color: white;">
    <h2 style="color:var(--neon); margin-bottom: 20px;">💳 SELECT METHOD</h2>
    
    <!-- ৪টি মেথড বাটন -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
        <div onclick="selectMethod('Bkash Personal', '<?php echo $nums['bkash_per'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:10px; border-radius:10px; border:1px solid #d12053; cursor:pointer;">
            <img src="https://betlover777.com" width="30" onerror="this.src='https://placeholder.com'"><br><small>বিকাশ পারসোনাল</small>
        </div>
        <div onclick="selectMethod('Bkash Agent', '<?php echo $nums['bkash_agt'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:10px; border-radius:10px; border:1px solid #d12053; cursor:pointer;">
            <img src="https://betlover777.com" width="30" onerror="this.src='https://placeholder.com'"><br><small>বিকাশ এজেন্ট</small>
        </div>
        <div onclick="selectMethod('Nagad Personal', '<?php echo $nums['nagad_per'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:10px; border-radius:10px; border:1px solid #f7941d; cursor:pointer;">
            <img src="https://betlover777.com" width="30" onerror="this.src='https://placeholder.com'"><br><small>নগদ পারসোনাল</small>
        </div>
        <div onclick="selectMethod('Nagad Agent', '<?php echo $nums['nagad_agt'] ?? 'বন্ধ'; ?>')" style="background:#1a1a1a; padding:10px; border-radius:10px; border:1px solid #f7941d; cursor:pointer;">
            <img src="https://betlover777.com" width="30" onerror="this.src='https://placeholder.com'"><br><small>নগদ এজেন্ট</small>
        </div>
    </div>

    <!-- নম্বর কপি বক্স -->
    <div id="numberBox" style="display:none; background:rgba(0,255,136,0.1); padding:20px; border-radius:12px; border:1px dashed var(--neon); margin-bottom:20px;">
        <p id="methodName" style="color:#aaa; font-size:12px; margin:0;"></p>
        <h2 id="displayNum" style="color:var(--gold); margin:10px 0; font-size:24px;"></h2>
        <button onclick="copyNum()" style="background:var(--neon); color:#000; border:none; padding:8px 20px; border-radius:5px; font-weight:bold; cursor:pointer;">COPY NUMBER</button>
    </div>

    <!-- ডিপোজিট ফর্ম -->
    <div id="depositForm" style="display:none;">
        <input type="number" id="d_amount" placeholder="টাকার পরিমাণ" style="width:100%; padding:15px; background:#111; border:1px solid #333; color:white; border-radius:8px; margin-bottom:15px; box-sizing: border-box;">
        <input type="text" id="d_trx" placeholder="Transaction ID (TrxID)" style="width:100%; padding:15px; background:#111; border:1px solid #333; color:white; border-radius:8px; margin-bottom:20px; box-sizing: border-box;">
        <button onclick="submitDeposit()" style="width:100%; padding:15px; background:var(--gold); border:none; border-radius:8px; font-weight:bold; color:#000; cursor:pointer;">রিকোয়েস্ট পাঠান</button>
    </div>
</div>

<script>
let selectedMethod = "";
function selectMethod(m, num) {
    selectedMethod = m;
    document.getElementById('methodName').innerText = m + " (Cash Out)";
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
    if(!amount || !trx) { alert("সবগুলো ঘর পূরণ করুন!"); return; }

    let fd = new FormData();
    fd.append('amount', amount);
    fd.append('trx_id', trx);
    fd.append('method', selectedMethod);

    fetch('process_deposit.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') location.href='index.php';
    })
    .catch(() => alert("সার্ভার এরর!"));
}
</script>

<?php include 'footer.php'; ?>
