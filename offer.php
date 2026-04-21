<?php
include 'db.php';
include 'header.php'; // আপনার সেই সুন্দর হেডারটি এখানেও থাকবে

// ডাটাবেস থেকে অফার টেক্সট নিয়ে আসা
$res = $conn->query("SELECT setting_value FROM site_settings WHERE setting_key = 'offer_text'");
$offerText = ($res && $row = $res->fetch_assoc()) ? $row['setting_value'] : "বর্তমানে কোনো অফার নেই।";
?>

<div class="container" style="padding: 20px; text-align: center; margin-top: 20px;">
    <h1 style="color: var(--neon); text-shadow: 0 0 10px var(--neon);">SPECIAL OFFERS</h1>
    
    <div class="offer-card" style="background: rgba(0,255,136,0.1); border: 1.5px solid var(--neon); padding: 30px; border-radius: 15px; margin-top: 30px;">
        <p style="color: white; font-size: 18px; line-height: 1.6; font-weight: bold;">
            <?php echo nl2br(htmlspecialchars($offerText)); ?>
        </p>
    </div>

    <button onclick="location.href='index.php'" style="margin-top: 30px; background: var(--gold); color: black; padding: 12px 25px; border: none; border-radius: 5px; font-weight: 900; cursor: pointer;">
        BACK TO GAMES
    </button>
</div>

<?php include 'footer.php'; ?>
