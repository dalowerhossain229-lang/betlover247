<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

// ১. লগইন চেক
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$u = $_SESSION['user_id'];
// ইউজারের সব ডাটা একবারেই আনা
$u_data = $conn->query("SELECT * FROM users WHERE username = '$u'")->fetch_assoc();

// ২. অ্যাডমিন সেটিংস থেকে টার্গেটগুলো আনা (কলামের নামগুলো ডাটাবেসের সাথে মিলিয়ে নিন)
$st = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
$t_main = (float)($st['main_target'] ?? 1000);
$t_bonus = (float)($st['bonus_target'] ?? 5000);
$t_pb = (float)($st['pb_target'] ?? 10000);

// ৩. ৩টি ব্যালেন্স একদম আলাদা করা (পিবি এখন আর মেইনের সাথে যোগ হবে না)
$main_b = (float)($u_data['balance'] ?? 0);
$pb_b = (float)($u_data['pb_balance'] ?? 0); // PB আলাদা কলাম থেকে আসবে
$bonus_b = (float)($u_data['bonus_balance'] ?? 0);

// ৪. ৩টি টার্নওভারের ডাটা নেওয়া
$main_t = (float)($u_data['turnover'] ?? 0);
$bonus_t = (float)($u_data['bonus_turnover'] ?? 0);
$pb_t = (float)($u_data['pb_turnover'] ?? 0);

// প্রগ্রেস বার ক্যালকুলেশন ফাংশন
function getBar($done, $target) {
    $p = ($target > 0) ? ($done / $target) * 100 : 0;
    return ($p > 100) ? 100 : $p;
}
?>


<div style="padding: 15px; text-align: center; color: white; font-family: sans-serif; background: #0a0b10; min-height: 100vh;">
    
    <!-- ৩টি ব্যালেন্স বক্স -->
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 20px;">
        <!-- মেইন বক্স -->
        <div style="background:#111; border:1px solid #00ff88; padding:10px; border-radius:10px;">
            <small style="font-size:9px; color:#888;">MAIN</small>
            <div style="color:#00ff88; font-size:12px; font-weight:bold;">৳<?php echo number_format($main_b, 2); ?></div>
        </div>
        <!-- পিবি বক্স -->
        <div style="background:#111; border:1px solid #00bcd4; padding:10px; border-radius:10px;">
            <small style="font-size:9px; color:#888;">PB</small>
            <div style="color:#00bcd4; font-size:12px; font-weight:bold;">৳<?php echo number_format($pb_b, 2); ?></div>
        </div>
        <!-- বোনাস বক্স -->
        <div style="background:#111; border:1px solid #ffdf1b; padding:10px; border-radius:10px;">
            <small style="font-size:9px; color:#888;">BONUS</small>
            <div style="color:#ffdf1b; font-size:12px; font-weight:bold;">৳<?php echo number_format($bonus_b, 2); ?></div>
        </div>
    </div>


    <!-- ৩টি টার্নওভার লাইন (নকশা অনুযায়ী) -->
    <div style="background: #111; padding: 20px; border-radius: 20px; text-align: left; border: 1px solid #222;">
        <!-- মেইন টার্নওভার -->
        <!-- ১. মেইন টার্নওভার -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                <span style="color: #00ff88; font-weight: bold;">Main Turnover</span>
                <span><?php echo number_format($main_t, 0); ?> / <?php echo number_format($t_main, 0); ?></span>
            </div>
            <div style="width: 100%; background: #222; height: 4px; border-radius: 10px;">
                <div style="width: <?php echo getBar($main_t, $t_main); ?>%; background: #00ff88; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- ২. পিবি টার্নওভার -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                <span style="color: #00bcd4; font-weight: bold;">PB Turnover</span>
                <span><?php echo number_format($pb_t, 0); ?> / <?php echo number_format($t_pb, 0); ?></span>
            </div>
            <div style="width: 100%; background: #222; height: 4px; border-radius: 10px;">
                <div style="width: <?php echo getBar($pb_t, $t_pb); ?>%; background: #00bcd4; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- ৩. বোনাস টার্নওভার -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                <span style="color: #ffdf1b; font-weight: bold;">Bonus Turnover</span>
                <span><?php echo number_format($bonus_t, 0); ?> / <?php echo number_format($t_bonus, 0); ?></span>
            </div>
            <div style="width: 100%; background: #222; height: 4px; border-radius: 10px;">
                <div style="width: <?php echo getBar($bonus_t, $t_bonus); ?>%; background: #ffdf1b; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

    <!-- বোনাস ক্লেইম বাটন -->
    <div style="margin-top: 20px;">
        <?php if($bonus_t >= $t_bonus && $bonus_b > 0): ?>
            <button onclick="claimBonus()" style="width:100%; padding:15px; background:#00ff88; border:none; border-radius:12px; color:#000; font-weight:bold; cursor:pointer;">CLAIM BONUS TO MAIN</button>
        <?php else: ?>
            <button disabled style="width:100%; padding:15px; background:#1a1a1a; border:1px solid #333; border-radius:12px; color:#666; font-weight:bold;">🔒 BONUS LOCKED</button>
        <?php endif; ?>
    </div>

    <!-- মেনু বাটনসমূহ -->
    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 25px;">
        <button class="p-btn" onclick="location.href='deposit.php'">Deposit ></button>
        <button class="p-btn" onclick="location.href='withdraw.php'">Withdraw ></button>
        <button class="p-btn" onclick="togglePayment()">Payment Numbers (Lock) ></button>
        <button class="p-btn" onclick="location.href='logout.php'" style="color:#ff4d4d;">Logout</button>
    </div>

    <!-- পেমেন্ট লক বক্স -->
    <div id="paymentBox" style="display:none; background:#111; padding:15px; border-radius:12px; border:1px solid #00ff88; margin-top:10px;">
        <h4 style="color:#00ff88; font-size:12px;">LOCK PAYMENT NUMBERS</h4>
        <input type="number" id="bk" placeholder="Bkash" style="width:90%; background:#000; border:1px solid #333; color:#fff; padding:8px; margin-bottom:10px;">
        <input type="number" id="ng" placeholder="Nagad" style="width:90%; background:#000; border:1px solid #333; color:#fff; padding:8px; margin-bottom:10px;">
        <button onclick="saveNumbers()" style="background:#00ff88; width:100%; padding:10px; border-radius:8px; font-weight:bold;">LOCK NOW</button>
    </div>
</div>

<script>
function togglePayment() { const x = document.getElementById('paymentBox'); x.style.display = x.style.display === 'none' ? 'block' : 'none'; }

function claimBonus() {
    if(confirm("Claim bonus to main?")) {
        fetch('api_claim_bonus.php').then(r => r.json()).then(d => { alert(d.message); if(d.status==='success') location.reload(); });
    }
}

function saveNumbers() {
    const bk = document.getElementById('bk').value;
    const ng = document.getElementById('ng').value;
    if(!bk && !ng) return alert("Enter number!");
    if(confirm("Lock numbers permanently?")) {
        let fd = new FormData(); fd.append('bkash', bk); fd.append('nagad', ng);
        fetch('api_save_payment.php', {method:'POST', body:fd}).then(r => r.json()).then(d => { alert(d.message); if(d.status==='success') location.reload(); });
    }
}
</script>

<style> .p-btn { width:100%; padding:15px; background:#0a0f0d; border:1px solid #1a2a22; border-radius:12px; color:white; text-align:left; font-size:13px; cursor:pointer; } </style>
<?php include 'footer.php'; ?>
            
