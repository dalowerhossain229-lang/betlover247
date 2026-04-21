<?php
include 'header.php'; // আপনার সেই নিওন হেডারটি এখানেও থাকবে
?>

<div class="promo-container" style="padding: 15px; background: var(--dark); min-height: 100vh; padding-bottom: 90px;">
    <h2 style="color: var(--neon); text-align: center; margin: 20px 0; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">Exclusive Promotions</h2>

    <!-- ১. Insurance Card -->
    <div class="promo-card" onclick="openPromoForm('Insurance')">
        <div class="card-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="card-info">
            <h3>INSURANCE</h3>
            <p>নিরাপদ গেমিং এবং লস রিকভারি সুবিধা।</p>
        </div>
        <i class="fa-solid fa-chevron-right arrow"></i>
    </div>

    <!-- ২. Investment Card -->
    <div class="promo-card" onclick="openPromoForm('Investment')">
        <div class="card-icon"><i class="fa-solid fa-chart-line"></i></div>
        <div class="card-info">
            <h3>INVESTMENT</h3>
            <p>আপনার ব্যালেন্স ইনভেস্ট করুন এবং মুনাফা নিন।</p>
        </div>
        <i class="fa-solid fa-chevron-right arrow"></i>
    </div>

    <!-- ৩. Health Card Card -->
    <div class="promo-card" onclick="openPromoForm('Health Card')">
        <div class="card-icon"><i class="fa-solid fa-id-card-medical"></i></div>
        <div class="card-info">
            <h3>HEALTH CARD</h3>
            <p>মেম্বারদের জন্য স্পেশাল মেডিকেল ডিসকাউন্ট।</p>
        </div>
        <i class="fa-solid fa-chevron-right arrow"></i>
    </div>

    <!-- ৪. Affiliate Apply Card -->
    <div class="promo-card" onclick="openPromoForm('Affiliate Apply')">
        <div class="card-icon"><i class="fa-solid fa-users-rays"></i></div>
        <div class="card-info">
            <h3>AFFILIATE APPLY</h3>
            <p>পার্টনার হয়ে লাইফটাইম কমিশন ইনকাম করুন।</p>
        </div>
        <i class="fa-solid fa-chevron-right arrow"></i>
    </div>

    <!-- ৫. Sudden Bonus Card -->
    <div class="promo-card" onclick="openPromoForm('Sudden Bonus')">
        <div class="card-icon"><i class="fa-solid fa-bolt"></i></div>
        <div class="card-info">
            <h3>SUDDEN BONUS</h3>
            <p>একটিভ থাকুন এবং সারপ্রাইজ বোনাস বুঝে নিন।</p>
        </div>
        <i class="fa-solid fa-chevron-right arrow"></i>
    </div>
</div>

<!-- প্রমোশন ফর্ম মোডাল (পপ-আপ) -->
<div id="promoModal" class="modal-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); justify-content: center; align-items: center; z-index: 10000;">
    <div class="modal-content" style="background: #073128; border: 2px solid var(--neon); padding: 25px; border-radius: 15px; width: 300px; text-align: center; position: relative;">
        <span onclick="closePromoForm()" style="position: absolute; top: 10px; right: 15px; color: #fff; cursor: pointer; font-size: 24px;">&times;</span>
        <h3 id="promoTitle" style="color: var(--neon); margin-bottom: 20px; text-transform: uppercase;">FORM</h3>
        
        <div id="promoFormContent">
            <!-- আপনি যখন ফর্মের বিষয়গুলো বলবেন তখন এখানে ইনপুট বক্স বসাবো -->
            <p style="color: #ccc; font-size: 13px;">এই ফর্মের অপশনগুলো দ্রুত আপডেট করা হচ্ছে...</p>
        </div>
    </div>
</div>

<style>
    .promo-card {
        background: linear-gradient(135deg, #0a1f1a, #000);
        border: 1px solid rgba(0, 255, 136, 0.15);
        padding: 18px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
        margin-bottom: 15px;
        transition: 0.3s;
    }
    .promo-card:hover { border-color: var(--neon); transform: scale(1.02); box-shadow: 0 0 15px rgba(0,255,136,0.1); }
    .card-icon { background: rgba(0, 255, 136, 0.1); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--neon); font-size: 22px; }
    .card-info h3 { margin: 0; font-size: 15px; color: #fff; font-weight: 800; letter-spacing: 0.5px; }
    .card-info p { margin: 4px 0 0; font-size: 11px; color: #888; }
    .arrow { margin-left: auto; color: #333; font-size: 14px; }
</style>

<script>
    function openPromoForm(type) {
        document.getElementById('promoTitle').innerText = type + " Application";
        document.getElementById('promoModal').style.display = 'flex';
    }
    function closePromoForm() {
        document.getElementById('promoModal').style.display = 'none';
    }
</script>

<?php include 'footer.php'; ?>
