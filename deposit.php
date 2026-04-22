<?php 
session_start();
include 'header.php'; 
include 'db.php'; 

// ডাটাবেস থেকে এডমিনের সেট করা নম্বরগুলো নিয়ে আসা
$bkash_res = $conn->query("SELECT config_value FROM site_configs WHERE config_key = 'bkash_num'");
$bkash_num = ($bkash_res && $bkash_res->num_rows > 0) ? $bkash_res->fetch_assoc()['config_value'] : 'অ্যাডমিন সেট করেনি';

$nagad_res = $conn->query("SELECT config_value FROM site_configs WHERE config_key = 'nagad_num'");
$nagad_num = ($nagad_res && $nagad_res->num_rows > 0) ? $nagad_res->fetch_assoc()['config_value'] : 'অ্যাডমিন সেট করেনি';
?>

<div style="padding: 20px; text-align: center; color: white;">
    <h2 style="color:var(--neon); margin-bottom: 20px;">DEPOSIT</h2>

    <!-- নম্বর কার্ড (বিকাশ ও নগদ) -->
    <div style="background:rgba(0,255,136,0.05); padding:15px; border-radius:12px; border:1px dashed var(--neon); margin-bottom:20px; text-align:center;">
        <p style="color:#aaa; font-size:12px; margin:0;">Bkash/Nagad (Personal - Cash Out)</p>
        
        <!-- বিকাশ সেকশন -->
        <div style="margin:10px 0;">
            <h2 style="color:var(--gold); margin:5px 0; font-size:24px;"><?php echo $bkash_num; ?></h2>
            <span style="background:var(--neon); color:#000; padding:2px 10px; border-radius:20px; font-size:10px; font-weight:bold; text-transform:uppercase;">Bkash</span>
        </div>

        <hr style="border:0.1px solid rgba(0,255,136,0.2); margin:15px 0;">

        <!-- নগদ সেকশন -->
        <div style="margin:10px 0;">
            <h2 style="color:var(--gold); margin:5px 0; font-size:24px;"><?php echo $nagad_num; ?></h2>
            <span style="background:#ffdf1b; color:#000; padding:2px 10px; border-radius:20px; font-size:10px; font-weight:bold; text-transform:uppercase;">Nagad</span>
        </div>
    </div>

    <!-- ডিপোজিট ফর্ম -->
    <input type="number" id="d_amount" placeholder="টাকার পরিমাণ" style="width:100%; padding:15px; background:#111; border:1px solid #333; color:white; border-radius:8px; margin-bottom:15px; box-sizing: border-box;">
    <input type="text" id="d_trx" placeholder="TrxID (ট্রানজেকশন আইডি)" style="width:100%; padding:15px; background:#111; border:1px solid #333; color:white; border-radius:8px; margin-bottom:20px; box-sizing: border-box;">

    <button onclick="submitDeposit()" style="width:100%; padding:15px; background:var(--gold); border:none; border-radius:8px; font-weight:bold; cursor:pointer; color:#000;">রিকোয়েস্ট পাঠান</button>
    
    <p onclick="location.href='index.php'" style="color:#555; margin-top:15px; cursor:pointer;">বন্ধ করুন</p>
</div>

<?php include 'footer.php'; ?>
