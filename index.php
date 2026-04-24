<?php 
include 'header.php'; 
?>
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

<!-- ডাইনামিক স্লাইডার সেকশন শুরু -->
<div class="slider-container">
    <div class="slider">
        <?php 
        $get_slides = $conn->query("SELECT * FROM slider_images LIMIT 10");
        if($get_slides && $get_slides->num_rows > 0):
            while($s = $get_slides->fetch_assoc()): ?>
                <div class="slide">
                    <img src="<?php echo $s['image_path']; ?>" alt="Slide">
                </div>
            <?php endwhile; 
        else: ?>
            <!-- যদি কোনো ছবি আপলোড করা না থাকে তবে নিচের ডিফল্ট ছবি দেখাবে -->
            <div class="slide"><img src="images/default_banner.png" alt="Default"></div>
        <?php endif; ?>
    </div>
</div>
<!-- ডাইনামিক স্লাইডার সেকশন শেষ -->

<!-- গেম গ্রিড সেকশন শুরু -->
<div class="game-grid">
    <!-- ১. Super Ace -->
    <div class="game-card" onclick="playGame('super-ace')">
        <img src="super-ace.png" alt="Super Ace">
        <p>SUPER ACE</p>
    </div>

    <!-- ২. Aviator -->
    <div class="game-card" onclick="playGame('aviator')">
        <img src="aviator.png" alt="Aviator">
        <p>AVIATOR</p>
    </div>

    <!-- ৩. Chicken Road -->
    <div class="game-card" onclick="playGame('chicken')">
        <img src="chicken.png" alt="Chicken Road">
        <p>CHICKEN ROAD</p>
    </div>

    <!-- ৪. Lucky 777 -->
    <div class="game-card" onclick="playGame('lucky-777')">
        <img src="lucky-777.png" alt="Lucky 777">
        <p>LUCKY 777</p>
    </div>

    <!-- ৫. Crazy Time -->
    <div class="game-card" onclick="playGame('crazy-time')">
        <img src="crazy-time.png" alt="Crazy Time">
        <p>CRAZY TIME</p>
    </div>

    <!-- ৬. Mega Ace -->
    <div class="game-card" onclick="playGame('mega-ace')">
        <img src="mega-ace.png" alt="Mega Ace">
        <p>MEGA ACE</p>
    </div>

    <!-- ৭. Mega Wheel -->
    <div class="game-card" onclick="playGame('mega-wheel')">
        <img src="mega-wheel.png" alt="Mega Wheel">
        <p>MEGA WHEEL</p>
    </div>

    <!-- ৮. Better Ace -->
    <div class="game-card" onclick="playGame('better-ace')">
        <img src="better-ace.png" alt="Better Ace">
        <p>BETTER ACE</p>
    </div>

    <!-- ৯. Cricket -->
    <div class="game-card" onclick="playGame('cricket')">
        <img src="cricket.png" alt="Cricket">
        <p>CRICKET</p>
    </div>

    <!-- ১০. Football -->
    <div class="game-card" onclick="playGame('football')">
        <img src="football.png" alt="Football">
        <p>FOOTBALL</p>
    </div>

    <!-- ১১. Lottery -->
    <div class="game-card" onclick="playGame('lottery')">
        <img src="lottery.png" alt="Lottery">
        <p>LOTTERY</p>
    </div>

    <!-- ১২. Ludu -->
    <div class="game-card" onclick="playGame('ludu')">
        <img src="ludu.png" alt="Ludu">
        <p>LUDU</p>
    </div>

    <!-- ১৩. Wheel -->
    <div class="game-card" onclick="playGame('wheel')">
        <img src="wheel.png" alt="Wheel">
        <p>WHEEL</p>
    </div>

    <!-- ১৪. Slot -->
    <div class="game-card" onclick="playGame('slot')">
        <img src="slot.png" alt="Slot">
        <p>SLOT</p>
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
        <!-- রেফার কোড ইনপুট (ঐচ্ছিক) -->
<input type="text" id="regRef" placeholder="Referral Code (Optional)" value="<?php echo $_GET['ref'] ?? ''; ?>" style="width: 100%; padding: 12px; margin-top: 10px; background: #000; border: 1px solid #333; color: #fff; border-radius: 8px; outline: none;">

        <button onclick="processRegister()" class="btn-submit-reg" style="background: var(--gold); color: #000; width: 100%; padding: 12px; border: none; border-radius: 8px; font-weight: 900; margin-top: 15px; cursor: pointer;">একাউন্ট খুলুন</button>
        
        <p onclick="closeAll()" style="color: #555; margin-top: 15px; cursor: pointer; font-size: 13px;">বন্ধ করুন</p>
    </div>
</div>


<style>
/* ইনডেক্স পেজ স্টাইল */
.slider-container {
    width: 94%;
    margin: 15px auto;
    height: 160px;
    border-radius: 15px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.5);
}
.slider {
    display: flex;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
}
.slide {
    min-width: 100%;
    height: 100%;
}
.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ২. গেম কার্ড গ্লোয়িং ইফেক্ট */
.game-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    padding: 15px;
}
.game-card {
    background: #1a1a1a;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #333;
    transition: 0.3s;
}
.game-card:active { transform: scale(0.95); }
.game-card img {
    width: 100%;
    height: 110px;       /* সব ছবির উচ্চতা একই ফিক্সড করে দেওয়া হলো */
    object-fit: cover;    /* ছবি যাতে চ্যাপ্টা না হয়, সুন্দরভাবে ফিট হয় */
    display: block;
    background: #111;     /* ছবি লোড হওয়ার আগে কালো ব্যাকগ্রাউন্ড দেখাবে */
}

.game-card p {
    font-size: 10px;
    padding: 5px;
    text-align: center;
    color: #888;
}
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
    let currentSlide = 0;
    setInterval(() => {
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        if (slider && slides.length > 1) {
            currentSlide = (currentSlide + 1) % slides.length;
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
        }
    }, 3000);
</script>

<?php include 'footer.php'; ?>
