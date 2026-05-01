<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

// ১. অ্যাডমিন প্যানেলের নম্বরগুলো একবারে নিয়ে আসা
$st_res = $conn->query("SELECT * FROM settings WHERE id = 1");
$st = $st_res->fetch_assoc();

$bk_p = $st['admin_bkash_p'] ?? '017XXXXXXXX';
$bk_a = $st['admin_bkash_a'] ?? '017XXXXXXXX';
$ng_p = $st['admin_nagad_p'] ?? '018XXXXXXXX';
$ng_a = $st['admin_nagad_a'] ?? '018XXXXXXXX';
?>

<div style="padding: 20px; text-align: center; color: white; font-family: sans-serif;">
    <h2 style="color:#00ff88; text-shadow: 0 0 10px rgba(0,255,136,0.5);">💳 SELECT METHOD</h2>
    <p style="font-size: 12px; color: #888; margin-bottom: 20px;">টাকা পাঠিয়ে নিচের তথ্যগুলো পূরণ করুন</p>
            <!-- PB Promotional Banner -->
        <div onclick="location.href='pb_deposit.php'" style="background: linear-gradient(90deg, #1a0a00, #4d2600); border: 1px solid #ffdf1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; cursor: pointer; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 15px rgba(255,223,27,0.1);">
            <div style="background: #ffdf1b; color: #000; padding: 10px; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px;">🎁</div>
            <div style="text-align: left;">
                <h4 style="color: #ffdf1b; margin: 0; font-size: 14px; text-transform: uppercase;">Promotional PB Offer!</h4>
                <p style="color: #fff; margin: 5px 0 0 0; font-size: 11px;">বোনাস ও অফার নিতে এখানে ক্লিক করুন!</p>
            </div>
            <div style="margin-left: auto; color: #ffdf1b; font-weight: bold;">></div>
        </div>

    <!-- ৪টি মেথড বাটন -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 25px;">
       <div onclick="selectMethod('Bkash Personal', '<?php echo $st['admin_bkash_p']; ?>')" style="background:#111; padding:15px; border-radius:10px; border:1px solid #333; cursor:pointer; text-align:center;">
               <img src="https://github.io" width="35" onerror="this.src='https://icons8.com'"><br><small>বিকাশ পারসোনাল</small> 
            </div>

            <div onclick="selectMethod('Bkash Agent', '<?php echo $st['admin_bkash_a']; ?>')" style="background:#111; padding:15px; border-radius:10px; border:1px solid #333; cursor:pointer; text-align:center;">
               <img src="https://github.io" width="35" onerror="this.src='https://icons8.com'"><br><small>বিকাশ এজেন্ট</small> 
            </div>

            <div onclick="selectMethod('Nagad Personal', '<?php echo $st['admin_nagad_p']; ?>')" style="background:#111; padding:15px; border-radius:10px; border:1px solid #333; cursor:pointer; text-align:center;">
               <img src="https://github.io" width="35" onerror="this.src='https://icons8.com'"><br><small>নগদ পারসোনাল</small>
 
            </div>

            <div onclick="selectMethod('Nagad Agent', '<?php echo $st['admin_nagad_a']; ?>')" style="background:#111; padding:15px; border-radius:10px; border:1px solid #333; cursor:pointer; text-align:center;">
               <img src="https://github.io" width="35" onerror="this.src='https://icons8.com'"><br><small>নগদ এজেন্ট</small> 
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
    // পারসোনাল হলে 'Send Money' আর এজেন্ট হলে 'Cash Out' দেখাবে
    let type = m.includes('Personal') ? 'Send Money' : 'Cash Out';
    
    document.getElementById('methodName').innerText = m + " - " + type;
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
