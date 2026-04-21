<?php
// ১. সেশন এবং হেডার ইনক্লুড
session_start();
include 'db.php';
include 'header.php'; 
?>

<div class="promo-page-wrapper" style="padding: 15px; background: var(--dark); min-height: 100vh; padding-bottom: 100px;">
    <h2 style="color: var(--neon); text-align: center; margin: 20px 0; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 0 0 10px rgba(0,255,136,0.3);">Exclusive Schemes</h2>

    <!-- ৫টি কার্ডের গ্রিড -->
    <div class="promo-grid" style="display: grid; gap: 15px; max-width: 500px; margin: auto;">
        
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
                <p>ব্যালেন্স ইনভেস্ট করুন এবং মুনাফা অর্জন করুন।</p>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>

        <!-- ৩. Health Card -->
        <div class="promo-card" onclick="openPromoForm('Health Card')">
            <div class="card-icon"><i class="fa-solid fa-id-card-medical"></i></div>
            <div class="card-info">
                <h3>HEALTH CARD</h3>
                <p>মেম্বারদের জন্য বিশেষ চিকিৎসা সেবা এবং ছাড়।</p>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>

        <!-- ৪. Affiliate Apply Card -->
        <div class="promo-card" onclick="openPromoForm('Affiliate Apply')">
            <div class="card-icon"><i class="fa-solid fa-users-rays"></i></div>
            <div class="card-info">
                <h3>AFFILIATE APPLY</h3>
                <p>আমাদের পার্টনার হয়ে লাইফটাইম ইনকাম শুরু করুন।</p>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>

        <!-- ৫. Sudden Bonus Card -->
        <div class="promo-card" onclick="openPromoForm('Sudden Bonus')">
            <div class="card-icon"><i class="fa-solid fa-bolt"></i></div>
            <div class="card-info">
                <h3>SUDDEN BONUS</h3>
                <p>যেকোনো সময় সারপ্রাইজ বোনাস পেতে চেক করুন।</p>
            </div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>

    </div>
</div>

<!-- ডায়নামিক প্রমোশন মোডাল -->
<div id="promoModal" class="modal-overlay" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); justify-content: center; align-items: center; z-index: 10000; overflow-y: auto; padding: 20px 0;">
    <div class="modal-content" style="background: #073128; border: 2px solid var(--neon); padding: 25px; border-radius: 15px; width: 90%; max-width: 350px; text-align: center; position: relative; margin: auto;">
        <span onclick="closePromoForm()" style="position: absolute; top: 10px; right: 15px; color: #fff; cursor: pointer; font-size: 28px; font-weight: bold;">&times;</span>
        <h3 id="promoTitle" style="color: var(--neon); margin-bottom: 20px; text-transform: uppercase; font-weight: 900; font-size: 18px;">FORM</h3>
        
        <form id="actualPromoForm" onsubmit="event.preventDefault(); submitPromoForm();">
            <div id="promoFormContent">
                <!-- ডায়নামিক কন্টেন্ট এখানে লোড হবে -->
            </div>
        </form>
    </div>
</div>

<style>
    .promo-card { background: linear-gradient(135deg, #0a1f1a, #000); border: 1px solid rgba(0, 255, 136, 0.2); padding: 18px; border-radius: 12px; display: flex; align-items: center; gap: 15px; cursor: pointer; transition: 0.3s; }
    .promo-card:hover { border-color: var(--neon); transform: scale(1.02); box-shadow: 0 0 15px rgba(0,255,136,0.2); }
    .card-icon { background: rgba(0, 255, 136, 0.1); width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--neon); font-size: 20px; }
    .card-info h3 { margin: 0; font-size: 14px; color: #fff; font-weight: 800; letter-spacing: 0.5px; }
    .card-info p { margin: 4px 0 0; font-size: 11px; color: #888; }
    .arrow { margin-left: auto; color: #333; font-size: 14px; }

    .modal-input { width: 100%; padding: 12px; margin-bottom: 12px; border-radius: 8px; border: 1px solid #1a2a22; background: #000; color: #fff; box-sizing: border-box; font-size: 13px; outline: none; }
    .modal-input:focus { border-color: var(--neon); }
    .file-label { display: block; text-align: left; font-size: 10px; color: var(--neon); margin-bottom: 5px; text-transform: uppercase; font-weight: bold; }
    .btn-submit-promo { width: 100%; padding: 14px; background: var(--neon); color: #000; border: none; font-weight: 900; border-radius: 8px; cursor: pointer; text-transform: uppercase; margin-top: 10px; font-size: 14px; }
</style>

<script>
function openPromoForm(type) {
    const title = document.getElementById('promoTitle');
    const content = document.getElementById('promoFormContent');
    title.innerText = type + " Application";
    document.getElementById('promoModal').style.display = 'flex';

    if (type === 'Insurance') {
        content.innerHTML = `
            <input type="text" placeholder="আপনার নাম" class="modal-input" required>
            <input type="text" placeholder="বাবার নাম" class="modal-input" required>
            <input type="text" placeholder="মায়ের নাম" class="modal-input" required>
            <input type="text" placeholder="এনআইডি নম্বর" class="modal-input" required>
            <label class="file-label">এনআইডি ছবি ও ফ্যামিলি ছবি:</label>
            <input type="file" class="modal-input" required>
            <label class="file-label">হারের প্রমাণস্বরুপ ডকুমেন্ট:</label>
            <input type="file" class="modal-input" required>
            <button type="submit" class="btn-submit-promo">আবেদন পাঠান</button>`;
    } 
    else if (type === 'Investment') {
        content.innerHTML = `
            <input type="number" placeholder="টাকার পরিমাণ" class="modal-input" required>
            <select class="modal-input" required>
                <option value="">সময় নির্বাচন করুন</option>
                <option value="3">৩ মাস (মিনিমাম)</option>
                <option value="6">৬ মাস</option>
                <option value="12">১২ মাস</option>
            </select>
            <select class="modal-input" required>
                <option value="">পেমেন্ট মেথড</option>
                <option value="bkash">বিকাশ (Personal)</option>
                <option value="nagad">নগদ (Personal)</option>
            </select>
            <label class="file-label">ট্রানজেকশন প্রুফ (Screenshot):</label>
            <input type="file" class="modal-input" required>
            <button type="submit" class="btn-submit-promo">বিনিয়োগ শুরু করুন</button>`;
    } 
    else if (type === 'Health Card') {
        content.innerHTML = `
            <input type="text" placeholder="আপনার নাম" class="modal-input" required>
            <input type="text" placeholder="বাবার নাম" class="modal-input" required>
            <input type="text" placeholder="মায়ের নাম" class="modal-input" required>
            <input type="text" placeholder="এনআইডি নম্বর" class="modal-input" required>
            <label class="file-label">এনআইডি ছবি ও মেডিকেল রিপোর্ট:</label>
            <input type="file" class="modal-input" required>
            <button type="submit" class="btn-submit-promo">কার্ডের আবেদন</button>`;
    } 
    else if (type === 'Affiliate Apply') {
        content.innerHTML = `
            <input type="text" placeholder="পূর্ণ নাম" class="modal-input" required>
            <input type="text" placeholder="এনআইডি নম্বর" class="modal-input" required>
            <label class="file-label">এনআইডি কার্ডের ছবি:</label>
            <input type="file" class="modal-input" required>
            <button type="submit" class="btn-submit-promo">এফিলিয়েট রিকোয়েস্ট</button>`;
    } 
    else if (type === 'Sudden Bonus') {
        content.innerHTML = `
            <input type="text" placeholder="আপনার ইউসার আইডি" class="modal-input" required>
            <p style="font-size:11px; color:var(--gold); margin-bottom:15px;">একটিভ ইউজারদের জন্য যেকোনো সময় সারপ্রাইজ বোনাস!</p>
            <button type="submit" class="btn-submit-promo">বোনাস চেক করুন</button>`;
    }
}

function closePromoForm() { document.getElementById('promoModal').style.display = 'none'; }

function submitPromoForm() {
    alert("আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে। এডমিন প্যানেল থেকে যাচাই শেষে আপনাকে জানানো হবে।");
    closePromoForm();
}

// মোডালের বাইরে ক্লিক করলে বন্ধ হওয়া
window.onclick = function(event) {
    if (event.target == document.getElementById('promoModal')) { closePromoForm(); }
}
</script>

<?php include 'footer.php'; ?>
            
