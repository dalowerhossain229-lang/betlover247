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
    
           <!-- এডমিন নিয়ন্ত্রিত পারসোনাল নম্বর কার্ড -->
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
<?php include 'footer.php'; ?>
