<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$u = $_SESSION['user_id'];
$u_data = $conn->query("SELECT * FROM users WHERE username = '$u'")->fetch_assoc();

// ১. মেইন টার্গেট = ইউজারের মোট সফল ডিপোজিট (সরাসরি ডাটাবেস থেকে)
$dep_res = $conn->query("SELECT SUM(amount) as t_dep FROM deposits WHERE username = '$u' AND (status = 'success' OR status = 'Approved')")->fetch_assoc();
$t_main = (float)($dep_res['t_dep'] ?? 0); 

// ২. অ্যাডমিন থেকে বোনাস ও পিবি টার্গেট আনা
$st = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
// এটি সরাসরি ইউজারের টেবিল থেকে ওই ইউজারের জন্য সেট করা টার্গেট নিয়ে আসবে
$t_bonus = isset($u_data['bonus_target']) ? (float)$u_data['bonus_target'] : 0;


// লাইন ১৭ এবং ১৮ এভাবে নিশ্চিত করুন

$t_pb = (float)($st['pb_target'] ?? 10000); // পিবি যেহেতু কাজ করছে, এর নিচের লাইনটিই বোনাস


// ৩. ব্যালেন্স ও টার্নওভার ডাটা
$main_b = (float)($u_data['balance'] ?? 0);
$pb_b = (float)($u_data['pb_balance'] ?? 0);
$bonus_b = (float)($u_data['bonus_balance'] ?? 0);

$main_t = (float)($u_data['turnover'] ?? 0);
$bonus_t = (float)($u_data['bonus_turnover'] ?? 0);
$pb_t = (float)($u_data['pb_turnover'] ?? 0);

// ৪. প্রগ্রেস বার ফাংশন
function getBar($done, $target) {
    $p = ($target > 0) ? ($done / $target) * 100 : 0;
    return ($p > 100) ? 100 : $p;
}
// ১. পিবি ব্যালেন্স অটো ট্রান্সফার লজিক (এটি আগের মতোই অটো থাকবে)
if ($t_pb > 0 && $pb_t >= $t_pb && $pb_b > 0) {
    $conn->query("UPDATE users SET balance = balance + $pb_b, pb_balance = 0 WHERE username = '$u'");
    echo "<script>window.location.href='profile.php?msg=pb_success';</script>";
    exit();
}

// ২. বোনাস ক্লেম লজিক (এটি শুধু ইউজার বাটনে ক্লিক করলে কাজ করবে)
if (isset($_GET['action']) && $_GET['action'] == 'claim_bonus') {
    if ($t_bonus > 0 && $bonus_t >= $t_bonus && $bonus_b > 0) {
        $conn->query("UPDATE users SET balance = balance + $bonus_b, bonus_balance = 0, bonus_target = 0 WHERE username = '$u'");
        $msg = "আপনার বোনাস মেইন ব্যালেন্সে যোগ করা হয়েছে!";
    }
}



// ৩. সাকসেস পপআপ স্ক্রিপ্ট
if (isset($msg)) {
    echo "
    <script src='https://jsdelivr.net'></script>
    <script>
        setTimeout(function() {
            Swal.fire({
                title: 'সাফল্য!',
                text: '$msg',
                icon: 'success',
                confirmButtonText: 'ঠিক আছে',
                confirmButtonColor: '#4caf50',
                background: '#1a1a1a',
                color: '#fff'
            }).then(() => { window.location.href='profile.php'; });
        }, 500);
    </script>";
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
        <!-- মাঝখানের PB বক্স -->
<div style="background:#111; border:1px solid #00bcd4; padding:10px; border-radius:10px;">
    <small style="font-size:9px; color:#888;">PB</small>
    <div style="color:#00bcd4; font-size:12px; font-weight:bold;">
        ৳<?php echo number_format($pb_b, 2); ?>
    </div>
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
               <span><?php echo ($main_t >= $t_main) ? '<b style="color: #4caf50;">✅ Complete</b>' : number_format($main_t, 0) . " / " . number_format($t_main, 0); ?></span>
 
            </div>
            <div style="width: 100%; background: #222; height: 4px; border-radius: 10px;">
                <div style="width: <?php echo getBar($main_t, $t_main); ?>%; background: #00ff88; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

        <!-- ২. পিবি টার্নওভার -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                <span style="color: #00bcd4; font-weight: bold;">PB Turnover</span>
                <span><?php echo ($pb_t >= $t_pb) ? '<b style="color: #4caf50;">✅ Complete</b>' : number_format($pb_t, 0) . " / " . number_format($t_pb, 0); ?></span>

            </div>
            <div style="width: 100%; background: #222; height: 4px; border-radius: 10px;">
                <div style="width: <?php echo getBar($pb_t, $t_pb); ?>%; background: #00bcd4; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>

           <!-- ৩. বোনাস টার্নওভার সেকশন (স্থায়ী সমাধান) -->
<div style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
        <span style="color: #ffc107; font-weight: bold;">Bonus Turnover</span>
        <span>
    <?php
    $t_bonus = isset($u_data['bonus_target']) ? (float)$u_data['bonus_target'] : 0;
    if ($bonus_t >= $t_bonus && $t_bonus > 0) {
        echo '<b style="color: #4caf50;">✅ Complete</b>';
    } else {
        echo number_format($bonus_t, 0) . " / " . number_format($t_bonus, 0);
    }
?>   
 </span>
    </div>
    <div style="width: 100%; background: #222; height: 4px; border-radius: 10px;">
     <div style="width: <?php echo getBar($bonus_t, $t_bonus); ?>%; background: #ffc107; height: 100%; border-radius: 10px;"></div>

    </div>
</div>
        <!-- বোনাস ক্লেইম বাটন -->
        <div style="margin-top: 20px;">
           <?php if ($t_bonus > 0 && $bonus_t >= $t_bonus && $bonus_b > 0): ?>
    <!-- টার্নওভার শেষ এবং ব্যালেন্স থাকলে এই বাটন দেখাবে -->
    <button onclick="claimBonus()" style="width: 100%; padding: 15px; background: #00ff88; color: #000; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
        CLAIM BONUS (৳ <?php echo number_format($bonus_b, 2); ?>)
    </button>
<?php elseif ($t_bonus > 0 && $bonus_t < $t_bonus): ?>
    <!-- টার্নওভার বাকি থাকলে এই বাটন দেখাবে -->
    <button disabled style="width: 100%; padding: 15px; background: #222; color: #888; border: 1px solid #333; border-radius: 8px;">
        🔒 BONUS LOCKED (<?php echo number_format($bonus_t); ?> / <?php echo number_format($t_bonus); ?>)
    </button>
<?php endif; ?>
        </div>


    <!-- মেনু বাটনসমূহ -->
    <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 25px;">
        <button class="p-btn" onclick="location.href='deposit.php'">Deposit ></button>
        <!-- সরাসরি লিঙ্ক ব্যবহারের জন্য এটি সবচেয়ে ভালো পদ্ধতি -->
<a href="withdraw.php" style="text-decoration: none; width: 100%;">
    <button style="width: 100%; padding: 15px; background: #222; color: #fff; border: 1px solid #333; border-radius: 8px; font-weight: bold; cursor: pointer;">
        Withdraw >
    </button>
</a>

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
   function claimBonus() {
    if(confirm("আপনি কি বোনাস টাকা মেইন ব্যালেন্সে যোগ করতে চান?")) {
        // একটি নতুন ফাইলে রিকোয়েস্ট পাঠানো অথবা বর্তমান পেজেই প্রসেস করা
        window.location.href = 'profile.php?action=claim_bonus';
    }
}
 
</script>

<style> .p-btn { width:100%; padding:15px; background:#0a0f0d; border:1px solid #1a2a22; border-radius:12px; color:white; text-align:left; font-size:13px; cursor:pointer; } </style>
<?php include 'footer.php'; ?>
            
