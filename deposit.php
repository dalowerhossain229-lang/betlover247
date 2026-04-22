<?php 
include 'header.php'; 
include 'db.php'; 

// ডাটাবেস থেকে এডমিনের সেট করা নম্বরগুলো নিয়ে আসা
$bkash_res = $conn->query("SELECT config_value FROM site_configs WHERE config_key = 'bkash_num'");
$bkash_num = ($bkash_res && $bkash_res->num_rows > 0) ? $bkash_res->fetch_assoc()['config_value'] : 'অ্যাডমিন সেট করেনি';

$nagad_res = $conn->query("SELECT config_value FROM site_configs WHERE config_key = 'nagad_num'");
$nagad_num = ($nagad_res && $nagad_res->num_rows > 0) ? $nagad_res->fetch_assoc()['config_value'] : 'অ্যাডমিন সেট করেনি';
?>

<div style="padding: 20px; text-align: center; max-width: 400px; margin: auto;">
    <h2 style="color:var(--neon);">DEPOSIT MONEY</h2>
    
    
        <!-- নতুন পেমেন্ট কার্ড (বিকাশ ও নগদ) -->
        <div style="background:#161b22; padding:15px; border-radius:10px; border:1px solid #333; margin-bottom:20px;">
            <p style="color:#888; font-size:12px; margin:0;">বিকাশ (Personal):</p>
            <h2 style="color:var(--gold); margin:5px 0;"><?php echo $bkash_num; ?></h2>
            
            <hr style="border:0.1px solid #222; margin:10px 0;">
            
            <p style="color:#888; font-size:12px; margin:0;">নগদ (Personal):</p>
            <h2 style="color:var(--gold); margin:5px 0;"><?php echo $nagad_num; ?></h2>
        </div>
    

    <input type="number" id="d_amount" class="modal-input" placeholder="টাকার পরিমাণ (Minimum 200)">
    <input type="text" id="d_trx" class="modal-input" placeholder="TrxID (ট্রানজেকশন আইডি)">
    
    <button class="btn-auth-submit" onclick="sendDeposit()">SUBMIT REQUEST</button>
</div>


<?php include 'footer.php'; ?>
