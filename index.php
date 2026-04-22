<?php 
include 'header.php'; 
?>

<?php include 'slider.php'; ?>

<!-- ২. নোটিশ বোর্ড -->
<div class="notice-board">
    <div class="notice-content">
        <i class="fa-solid fa-bullhorn"></i>
        <marquee scrollamount="5">
            <?php 
                // অ্যাডমিন প্যানেল থেকে নোটিশ নিয়ে আসা
                $n_res = $conn->query("SELECT config_value FROM site_configs WHERE config_key = 'notice_text'");
                if($n_res && $n_res->num_rows > 0) {
                    echo $n_res->fetch_assoc()['config_value'];
                } else {
                    echo "স্বাগতম BETLOVER777-এ!";
                }
            ?>
        </marquee>
    </div>
</div>



<!-- ৩. গেম গ্রিড -->
<div class="game-grid">
    <div class="game-card" onclick="location.href='superace/'">
        <img src="super-ace.png" alt="Super Ace">
        <p>SUPER ACE</p>
    </div>
    <div class="game-card" onclick="location.href='chickenroad/'">
        <img src="chicken-road.png" alt="Chicken Road">
        <p>CHICKEN ROAD</p>
    </div>
    <div class="game-card" onclick="location.href='wheel/'">
        <img src="wheel.png" alt="Wheel">
        <p>WHEEL</p>
    </div>
    <div class="game-card" onclick="location.href='cricket/'">
        <img src="cricket.png" alt="Cricket">
        <p>CRICKET</p>
    </div>

<div class="game-card" onclick="location.href='football/'">
        <img src="football.png" alt="football">
        <p>FOOTBALL</p>
    </div>
    
</div>

<!-- ৪. লগইন পপ-আপ (Modal) -->
<div id="loginModal" class="modal-overlay">
    <div class="modal-content">
        <h2 style="color:var(--neon); margin-top:0; font-size:20px;">LOGIN</h2>
        <input type="text" id="loginUser" class="modal-input" placeholder="ইউজার আইডি">
        <input type="password" id="loginPass" class="modal-input" placeholder="পাসওয়ার্ড">
        <button class="btn-auth-submit" onclick="processLogin()">প্রবেশ করুন</button>
        <p onclick="closeAll()" style="color:#888; font-size:12px; cursor:pointer; margin-top:15px; text-decoration:underline;">বন্ধ করুন</p>
    </div>
</div>
<!-- ৬. উজ্জ্বল ডিপোজিট মোডাল -->
<div id="depModal" class="modal-overlay">
    <div class="modal-content">
        <h2 style="color:var(--neon); margin-top:0; font-size:20px;">DEPOSIT</h2>
        
        <!-- বিকাশ/নগদ নম্বর কার্ড -->
        <div style="background:rgba(0,255,136,0.1); padding:10px; border-radius:10px; border:1px dashed var(--neon); margin-bottom:15px;">
            <p style="color:#ccc; font-size:10px; margin:0;">Bkash/Nagad (Cash Out)</p>
            <h3 style="color:var(--gold); margin:5px 0; font-size:18px;">017XXXXXXXX</h3>
            <span style="background:var(--neon); color:#000; padding:2px 8px; border-radius:5px; font-size:9px; font-weight:900;">AGENT</span>
        </div>

        <input type="number" id="depAmount" class="modal-input" placeholder="টাকার পরিমাণ">
        <input type="text" id="depTrx" class="modal-input" placeholder="TrxID (ট্রানজেকশন আইডি)">
        
        <button class="btn-auth-submit" onclick="processDeposit()">রিকোয়েস্ট পাঠান</button>
        <p onclick="closeAll()" style="color:#888; font-size:12px; cursor:pointer; margin-top:15px;">বন্ধ করুন</p>
    </div>
</div>
<!-- Withdraw Modal -->
<div id="withdrawModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAll()">&times;</span>
        <h2 style="color: #ff4d4d;">WITHDRAW</h2>
        
        <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <p style="margin: 0; font-size: 13px;">আপনার নম্বর ও পরিমাণ দিন</p>
        </div>

        <input type="number" id="wdAmount" placeholder="টাকার পরিমাণ (৳)" class="modal-input">
        <input type="text" id="wdNumber" placeholder="বিকাশ/নগদ নম্বর" class="modal-input">
        
        <select id="wdMethod" class="modal-input" style="background: #000; color: #fff;">
            <option value="Bkash">Bkash</option>
            <option value="Nagad">Nagad</option>
        </select>
        
        <button class="btn-submit-dep" style="background: #ff4d4d;" onclick="processWithdraw()">উইথড্র রিকোয়েস্ট</button>
    </div>
</div>


<div id="regModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <span onclick="closeAll()" class="close-btn">&times;</span>
        <h2 style="color: var(--neon); margin-bottom: 20px;">REGISTER</h2>
        
        <!-- পুরো নাম এর বক্সটি সরিয়ে ফেলা হয়েছে -->
        <input type="text" id="regUser" placeholder="ইউজার আইডি (Username)" class="modal-input">
        <input type="password" id="regPass" placeholder="পাসওয়ার্ড (Password)" class="modal-input">
        
        <button onclick="processRegister()" class="btn-submit-reg" style="background: var(--gold); color: #000; width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: 900; margin-top: 15px; cursor: pointer;">একাউন্ট খুলুন</button>
        
        <p onclick="closeAll()" style="color: #555; margin-top: 15px; cursor: pointer; font-size: 13px;">বন্ধ করুন</p>
    </div>
</div>


<style>
/* ইনডেক্স পেজ স্টাইল */
.slider-container { margin: 15px auto; width: 94%; height: 105px; border-radius: 12px; overflow: hidden; border: 1px solid var(--neon); box-shadow: 0 0 10px rgba(0,255,136,0.2); }
.slider { display: flex; height: 100%; transition: 0.6s ease-in-out; }
.slide { min-width: 100%; display: flex; align-items: center; padding: 0 20px; position: relative; box-sizing: border-box; }
.slide-text h3 { margin: 0; color: var(--gold); font-size: 18px; text-transform: uppercase; }
.slide-text p { margin: 5px 0 0; font-size: 11px; opacity: 0.9; }
.bg-icon { position: absolute; right: 20px; font-size: 40px; opacity: 0.2; color: #fff; }

.notice-board { background: #000; border-top: 1px solid #14362a; border-bottom: 1px solid #14362a; padding: 8px 0; margin-bottom: 15px; }
.notice-content { display: flex; align-items: center; gap: 10px; color: var(--neon); font-size: 12px; font-weight: bold; padding: 0 10px; }

.game-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding: 0 10px; }
.game-card { background: #121212; border-radius: 8px; overflow: hidden; border: 1px solid #14362a; cursor: pointer; text-align: center; transition: 0.3s; }
.game-card:active { transform: scale(0.95); }
.game-card img { width: 100%; height: 75px; object-fit: cover; display: block; }
.game-card p { margin: 0; padding: 6px 0; font-size: 8px; font-weight: 900; color: var(--gold); background: rgba(0,0,0,0.85); letter-spacing: 0.5px; }

/* মোডাল ফিক্স (পপ-আপ সবার ওপরে থাকার জন্য) */
.modal-overlay { 
    position: fixed !important; 
    top: 0; left: 0; width: 100%; height: 100%; 
    background: rgba(0,0,0,0.85); 
    display: none; /* জাভাস্ক্রিপ্ট দিয়ে সচল হবে */
    justify-content: center; 
    align-items: center; 
    z-index: 999999 !important; 
    backdrop-filter: blur(5px);
}
.modal-content { 
    background: #161b22; width: 88%; max-width: 340px; 
    padding: 30px 25px; border-radius: 15px; 
    border: 1px solid var(--neon); 
    text-align: center; 
    box-shadow: 0 0 30px rgba(0,255,136,0.3); 
    animation: modal-fade 0.3s ease-out;
}
@keyframes modal-fade { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

.modal-input { 
    width: 100%; padding: 14px; margin-bottom: 15px; 
    background: #000; border: 1px solid #333; 
    color: white; border-radius: 10px; box-sizing: border-box; 
    outline: none; font-size: 14px;
}
.modal-input:focus { border-color: var(--neon); box-shadow: 0 0 5px var(--neon); }
.btn-auth-submit { 
    width: 100%; padding: 14px; background: var(--gold); 
    color: black; border: none; border-radius: 10px; 
    font-weight: 900; cursor: pointer; font-size: 14px; text-transform: uppercase;
}
</style>

<!-- জাভাস্ক্রিপ্ট এবং ফুটার -->
<script src="auth.js"></script>

<script>
// স্লাইডার অটোমেটিক চালানোর ছোট কোড
let currentSlide = 0;
setInterval(() => {
    currentSlide = (currentSlide + 1) % 2; // ২ টি স্লাইড আছে তাই
    const slider = document.getElementById('mainSlider');
    if(slider) slider.style.transform = `translateX(-${currentSlide * 100}%)`;
}, 4000);
</script>

<?php include 'footer.php'; ?>
