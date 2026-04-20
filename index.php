<?php 
include 'header.php'; // উপরের হেডার অংশ নিয়ে আসবে
?>

<!-- ১. উজ্জ্বল স্লাইডার সেকশন -->
<div class="slider-container">
    <div class="slider" id="mainSlider">
        <div class="slide" style="background: linear-gradient(45deg, #073128, #00ff88);">
            <div class="slide-text"><h3>Big Bonus</h3><p>প্রথম ডিপোজিটে ১০০% বোনাস!</p></div>
            <i class="fa-solid fa-coins bg-icon"></i>
        </div>
        <div class="slide" style="background: linear-gradient(45deg, #4b0082, #ff00ff);">
            <div class="slide-text"><h3>New Games</h3><p>সেরা সব নতুন গেম খেলুন।</p></div>
            <i class="fa-solid fa-gamepad bg-icon"></i>
        </div>
    </div>
</div>

<!-- ২. নোটিশ বোর্ড (উজ্জ্বল নিওন স্টাইল) -->
<div class="notice-board">
    <div class="notice-content">
        <i class="fa-solid fa-bullhorn"></i>
        <marquee>স্বাগতম BETLOVER777-এ! আমাদের নতুন অফার পেতে এখনই ডিপোজিট করুন। যেকোনো সমস্যায় কাস্টমার সার্ভিসে যোগাযোগ করুন।</marquee>
    </div>
</div>

<!-- ৩. গেম গ্রিড (৪ কলাম ডিজাইন) -->
<div class="game-grid">
    <!-- গেম ১: Super Ace -->
    <div class="game-card" onclick="location.href='superace/'">
        <img src="super-ace.png" alt="Super Ace">
        <p>SUPER ACE</p>
    </div>
    
    <!-- গেম ২: Chicken Road -->
    <div class="game-card" onclick="location.href='chickenroad/'">
        <img src="chicken-road.png" alt="Chicken Road">
        <p>CHICKEN ROAD</p>
    </div>

    <!-- গেম ৩: Wheel -->
    <div class="game-card" onclick="location.href='wheel/'">
        <img src="wheel.png" alt="Wheel">
        <p>WHEEL</p>
    </div>

    <!-- গেম ৪: Cricket -->
    <div class="game-card" onclick="location.href='cricket/'">
        <img src="cricket.png" alt="Cricket">
        <p>CRICKET</p>
    </div>
</div>

<style>
/* ইনডেক্স পেজের জন্য বিশেষ উজ্জ্বল CSS */
.slider-container { margin: 15px auto; width: 94%; height: 100px; border-radius: 12px; overflow: hidden; border: 1px solid var(--neon); }
.slider { display: flex; height: 100%; transition: 0.5s; }
.slide { min-width: 100%; display: flex; align-items: center; padding: 0 20px; position: relative; }
.slide-text h3 { margin: 0; color: var(--gold); font-size: 18px; }
.bg-icon { position: absolute; right: 20px; font-size: 40px; opacity: 0.3; }

.notice-board { background: #000; border-top: 1px solid #14362a; border-bottom: 1px solid #14362a; padding: 8px 10px; margin-bottom: 15px; }
.notice-content { display: flex; align-items: center; gap: 10px; color: var(--neon); font-size: 12px; font-weight: bold; }

.game-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; padding: 0 10px; }
.game-card { background: #121212; border-radius: 8px; overflow: hidden; border: 1px solid #14362a; cursor: pointer; text-align: center; }
.game-card img { width: 100%; height: 70px; object-fit: cover; }
.game-card p { margin: 0; padding: 5px 0; font-size: 8px; font-weight: 900; color: var(--gold); background: rgba(0,0,0,0.8); }
</style>
<!-- লগইন মোডাল -->
<div id="loginModal" class="modal-bg">
    <div class="modal-box">
        <h2 style="color:var(--neon)">LOGIN</h2>
        <input type="text" id="loginUser" placeholder="ইউজার আইডি">
        <input type="password" id="loginPass" placeholder="পাসওয়ার্ড">
        <button class="btn-auth" onclick="processLogin()">প্রবেশ করুন</button>
        <p onclick="closeAll()" style="font-size:12px; cursor:pointer; margin-top:10px;">বন্ধ করুন</p>
    </div>
</div>

<style>
.modal-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); display: none; justify-content: center; align-items: center; z-index: 9999; }
.modal-box { background: #161b22; padding: 30px; border-radius: 15px; border: 1px solid var(--neon); width: 85%; max-width: 350px; text-align: center; }
.modal-box input { width: 100%; padding: 12px; margin-bottom: 15px; background: #000; border: 1px solid #333; color: white; border-radius: 8px; box-sizing: border-box; outline: none; }
.modal-box input:focus { border-color: var(--neon); }
</style>
<script src="auth.js"></script>

<?php include 'footer.php'; ?>
