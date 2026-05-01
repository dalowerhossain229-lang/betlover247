<?php
ob_start();
session_start();
include 'db.php';

// লগইন চেক (হেডার ইনক্লুড করার আগেই করতে হবে)
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'header.php';
$u = $_SESSION['user_id'];

$u_data = $conn->query("SELECT * FROM users WHERE username = '$u'")->fetch_assoc();
// ১৫ নম্বর লাইন (যা আগে থেকেই আছে)
$u_data = $conn->query("SELECT * FROM users WHERE username = '$u'");
$user_data = $u_data->fetch_assoc(); $u_data = $user_data;

?>

<?php if (isset($user_data['is_affiliate']) && $user_data['is_affiliate'] == 1): ?>
    <div style="margin: 15px 10px;">
        <a href="affiliate.php" style="display: block; background: linear-gradient(90deg, #ffdf1b, #ffa500); color: #000; padding: 14px; border-radius: 12px; text-decoration: none; font-weight: bold; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            🤝 অ্যাফিলিয়েট প্যানেল
        </a>
    </div>
<?php endif; ?>

<?php
// ২৯ নম্বর লাইন থেকে আপনার বাকি সব কোড শুরু


// ১. মেইন টার্গেট = ইউজারের মোট সফল ডিপোজিট (সরাসরি ডাটাবেস থেকে)
$dep_res = $conn->query("SELECT SUM(amount) as t_dep FROM deposits WHERE username = '$u' AND (status = 'success' OR status = 'Approved')")->fetch_assoc();
$t_main = (float)($dep_res['t_dep'] ?? 0); 

// ২. অ্যাডমিন থেকে বোনাস ও পিবি টার্গেট আনা
$st = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
// এটি সরাসরি ইউজারের টেবিল থেকে ওই ইউজারের জন্য সেট করা টার্গেট নিয়ে আসবে
$t_bonus = isset($u_data['bonus_target']) ? (float)$u_data['bonus_target'] : 0;


// লাইন ১৭ এবং ১৮ এভাবে নিশ্চিত করুন

$t_pb = (float)($st['pb_target'] ?? 10000); // পিবি যেহেতু কাজ করছে, এর নিচের লাইনটিই বোনাস

// ৩. ব্যালেন্স ও টার্নওভার ডাটা (সংশোধিত)
$main_b = (float)($u_data['balance'] ?? 0);
$pb_b = (float)($u_data['pb_balance'] ?? 0);
$bonus_b = (float)($u_data['bonus_balance'] ?? 0);

// এখানে কলামের নামগুলো ডাটাবেসের সাথে মিলিয়ে দেওয়া হয়েছে
$main_t = (float)($u_data['main_t'] ?? 0);
$bonus_t = (float)($u_data['bonus_t'] ?? 0);
$pb_t = (float)($u_data['pb_t'] ?? 0);



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
            <div style="llll:#ffdf1b; font-size:12px; font-weight:bold;">৳<?php echo number_format($bonus_b, 2); ?></div>
        </div>
    </div>


    <!-- ৩টি টার্নওভার লাইন (নকশা অনুযায়ী) -->
    <div style="background: #111; padding: 20px; border-radius: 20px; text-align: left; border: 1px solid #222;">
        <!-- মেইন টার্নওভার -->
        <!-- ১. মেইন টার্নওভার -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                <span style="color: #00ff88; font-weight: bold;">Main Turnover</span>
        <?php 
if ($t_main > 0) {
    echo ($main_t >= $t_main) ? '<b style="color: #4caf50;">✅ Complete</b>' : number_format($main_t, 0) . " / " . number_format($t_main, 0);
} else {
    echo "0 / 0";
}
?>

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
<!-- পেমেন্ট নম্বর সেকশন (Hidable) -->
<div class="card" style="background: #111; padding: 10px; border-radius: 12px; border: 1px solid #333; margin-bottom: 12px; overflow: hidden;">
    
    <!-- এই হেডার বারটিতে ক্লিক করলে বক্স খুলবে -->
    <div onclick="toggleBox('payBox', 'payIcon')" style="cursor:pointer; display:flex; justify-content:space-between; align-items:center; padding:10px;">
        <h4 style="color:#00ff88; margin:0; font-size:15px;">📋 পেমেন্ট নম্বর (Locked)</h4>
        <span id="payIcon" style="color:#888;">▼</span>
    </div>
    
    <!-- এই অংশটি শুরুতে লুকানো থাকবে -->
    <div id="payBox" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 10px;">
        <form action="save_payment.php" method="POST" style="padding-top:15px; padding-bottom:15px;">
            
            <label style="color:#888; font-size:11px; display:block; margin-bottom:5px;">বিকাশ (Personal):</label>
            <input type="text" name="bkash" value="<?php echo $u_data['bkash_number'] ?? ''; ?>" 
                   placeholder="017XXXXXXXX" <?php if(!empty($u_data['bkash_number'])) echo 'readonly'; ?> 
                   style="width:100%; background:#222; color:#fff; padding:10px; border-radius:8px; border:1px solid #444; margin-bottom:12px; box-sizing:border-box; <?php if(!empty($u_data['bkash_number'])) echo 'opacity:0.6;'; ?>">

            <label style="color:#888; font-size:11px; display:block; margin-bottom:5px;">নগদ (Personal):</label>
            <input type="text" name="nagad" value="<?php echo $u_data['nagad_number'] ?? ''; ?>" 
                   placeholder="018XXXXXXXX" <?php if(!empty($u_data['nagad_number'])) echo 'readonly'; ?> 
                   style="width:100%; background:#222; color:#fff; padding:10px; border-radius:8px; border:1px solid #444; margin-bottom:15px; box-sizing:border-box; <?php if(!empty($u_data['nagad_number'])) echo 'opacity:0.6;'; ?>">

            <?php if(empty($u_data['bkash_number']) || empty($u_data['nagad_number'])): ?>
                <button type="submit" style="width:100%; background:#00ff88; color:#000; padding:12px; border-radius:8px; border:none; font-weight:bold; cursor:pointer;">নম্বর সেভ করুন</button>
            <?php else: ?>
                <p style="color: #ff4d4d; font-size: 11px; text-align: center; margin: 0;">🔒 নম্বরটি লক করা আছে। পরিবর্তনের জন্য সাপোর্ট টিমে যোগাযোগ করুন।</p>
            <?php endif; ?>
            
        </form>
    </div>
</div>

<!-- ট্রানজেকশন হিস্টোরি বক্স (Hidable) -->
<div class="card" style="background: #111; padding: 10px; border-radius: 12px; border: 1px solid #333; margin-bottom: 12px; overflow: hidden;">
    
    <!-- এই হেডার বারটিতে ক্লিক করলে বক্স খুলবে -->
    <div onclick="toggleBox('historyBox', 'histIcon')" style="cursor:pointer; display:flex; justify-content:space-between; align-items:center; padding:10px;">
        <h4 style="color:#ffdf1b; margin:0; font-size:15px;">📜 ট্রানজেকশন হিস্টোরি</h4>
        <span id="histIcon" style="color:#888;">▼</span>
    </div>
    
    <!-- এই অংশটি শুরুতে লুকানো থাকবে -->
    <div id="historyBox" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 10px;">
        <div style="padding-top:15px; padding-bottom:15px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: center;">
                <tr style="color: #888; border-bottom: 1px solid #333;">
                    <th style="padding: 10px;">তারিখ</th>
                    <th>ধরণ</th>
                    <th>পরিমাণ</th>
                    <th>অবস্থা</th>
                </tr>
                <?php
                // আপনার ডাটাবেস থেকে শেষ ১০টি লেনদেন আনা হচ্ছে
                $history = $conn->query("SELECT type, amount, status, date FROM (
                    SELECT 'Deposit' as type, amount, status, created_at as date FROM deposits WHERE username = '$u'
                    UNION ALL
                    SELECT 'Withdraw' as type, amount, status, created_at as date FROM withdraws WHERE username = '$u'
                ) as transactions ORDER BY date DESC LIMIT 10");

                if ($history && $history->num_rows > 0) {
                    while($row = $history->fetch_assoc()) {
                        // স্ট্যাটাস অনুযায়ী রং নির্ধারণ
                        $status_text = ucfirst($row['status']);
                        $status_color = ($row['status'] == 'success' || $row['status'] == 'Approved' || $row['status'] == '1') ? '#00ff88' : ($row['status'] == 'pending' ? '#ffdf1b' : '#ff4d4d');
                        
                        echo "<tr style='border-bottom: 1px solid #222;'>";
                        echo "<td style='padding: 10px; color: #bbb;'>" . date('d M', strtotime($row['date'])) . "</td>";
                        echo "<td>" . $row['type'] . "</td>";
                        echo "<td>৳" . number_format($row['amount'], 0) . "</td>";
                        echo "<td style='color: $status_color; font-weight: bold;'>$status_text</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='padding: 20px; text-align: center; color: #555;'>কোনো রেকর্ড পাওয়া যায়নি</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<!-- পাসওয়ার্ড পরিবর্তন বক্স (Hidable) -->
<div class="card" style="background: #111; padding: 10px; border-radius: 12px; border: 1px solid #333; margin-bottom: 12px; overflow: hidden;">
    
    <!-- এই হেডার বারটিতে ক্লিক করলে বক্স খুলবে -->
    <div onclick="toggleBox('passBox', 'passIcon')" style="cursor:pointer; display:flex; justify-content:space-between; align-items:center; padding:10px;">
        <h4 style="color:#ffdf1b; margin:0; font-size:15px;">🔐 পাসওয়ার্ড পরিবর্তন</h4>
        <span id="passIcon" style="color:#888;">▼</span>
    </div>
    
    <!-- এই অংশটি শুরুতে লুকানো থাকবে -->
    <div id="passBox" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; padding: 0 10px;">
        <form action="change_password.php" method="POST" style="padding-top:15px; padding-bottom:15px;">
            <label style="color:#888; font-size:11px; display:block; margin-bottom:5px;">বর্তমান পাসওয়ার্ড:</label>
            <input type="password" name="old_pass" placeholder="Current Password" required style="width:100%; background:#222; color:#fff; padding:10px; border-radius:8px; border:1px solid #444; margin-bottom:12px; box-sizing:border-box;">
            
            <label style="color:#888; font-size:11px; display:block; margin-bottom:5px;">নতুন পাসওয়ার্ড:</label>
            <input type="password" name="new_pass" placeholder="New Password" required style="width:100%; background:#222; color:#fff; padding:10px; border-radius:8px; border:1px solid #444; margin-bottom:15px; box-sizing:border-box;">
            
            <button type="submit" style="width:100%; background:#ffdf1b; color:#000; padding:12px; border-radius:8px; border:none; font-weight:bold; cursor:pointer;">আপডেট করুন</button>
        </form>
    </div>
</div>

 
<?php
// ১. নম্বরটি ডাটাবেস থেকে আনা
$wa_res = $conn->query("SELECT whatsapp_no FROM settings WHERE id = 1");
$wa_data = $wa_res->fetch_assoc();
$wa_no = preg_replace('/[^0-9]/', '', $wa_data['whatsapp_no'] ?? '8801306650323'); 
?>

<!-- ২. হোয়াটসঅ্যাপ বাটন (সাদা বক্সের ভেতরে) -->
<div class="card" style="background: #111; padding: 15px; border-radius: 12px; border: 1px solid #333; margin-bottom: 20px;">
    <button onclick="openWA()" style="width: 100%; display: flex; align-items: center; justify-content: center; background: #25D366; color: #fff; padding: 12px; border-radius: 10px; border: none; font-weight: bold; cursor: pointer; font-size: 14px;">
        <img src="https://wikimedia.org" width="18" style="margin-right: 10px;">
        হোয়াটসঅ্যাপ লাইভ চ্যাট
    </button>
</div>

<script>
function openWA() {
    // জাভাস্ক্রিপ্ট দিয়ে সরাসরি অ্যাপ ওপেন করার কমান্ড
    var number = "<?php echo $wa_no; ?>";
    window.open("https://api.whatsapp.com/send?phone=" + number + "&text=Hi, I need help!", "_blank");
}
</script>



  
        <button class="p-btn" onclick="location.href='logout.php'" style="color:#ff4d4d;">Logout</button>
    </div>



<script>
function togglePayment() { const x = document.getElementById('paymentBox'); x.style.display = x.style.display === 'none' ? 'block' : 'none'; }

function claimBonus() {
    if(confirm("Claim bonus to main?")) {
        fetch('api_claim_bonus.php').then(r => r.json()).then(d => { alert(d.message); if(d.status==='success') location.reload(); });
    }
}


   function claimBonus() {
    if(confirm("আপনি কি বোনাস টাকা মেইন ব্যালেন্সে যোগ করতে চান?")) {
        // একটি নতুন ফাইলে রিকোয়েস্ট পাঠানো অথবা বর্তমান পেজেই প্রসেস করা
        window.location.href = 'profile.php?action=claim_bonus';
    }
}
function toggleBox(id, iconId) {
    var content = document.getElementById(id);
    var icon = document.getElementById(iconId);
    if (content.style.maxHeight && content.style.maxHeight !== "0px") {
        content.style.maxHeight = "0px";
        icon.innerText = "▼";
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
        icon.innerText = "▲";
    }
}
 
</script>

<style> .p-btn { width:100%; padding:15px; background:#0a0f0d; border:1px solid #1a2a22; border-radius:12px; color:white; text-align:left; font-size:13px; cursor:pointer; } </style>
<?php include 'footer.php'; ?>
            
