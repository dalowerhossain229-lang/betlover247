<?php 
include 'header.php'; 
?>
<!-- ২. ডাইনামিক নোটিশ বোর্ড -->
<div style="background: #000; border-bottom: 1px solid #111; padding: 12px 0;">
    <div style="width: 94%; margin: 0 auto; display: flex; align-items: center; gap: 10px;">
        <span style="color: #00ff88; font-size: 18px;">📢</span>
        <marquee scrollamount="5" style="color: #00ff88; font-family: sans-serif; font-weight: bold; font-size: 13px; text-transform: uppercase;">
            <?php 
            $n_res = $conn->query("SELECT notice FROM settings WHERE id = 1");
            if ($n_res && $n_res->num_rows > 0) {
                $n_row = $n_res->fetch_assoc();
                echo $n_row['notice'];
            } else {
                echo "স্বাগতম BETLOVER777 - এ আমাদের সাথে থাকুন এবং জিতে নিন আকর্ষণীয় বোনাস!";
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

<!-- 🎡 ডাইনামিক ক্যাসিনো সুপ্রিম ৮-ক্যাটাগরি ফিল্টার বার নোড ভাই ভাই -->
<div class="lobby-category-scroll-bar">
    
    <!-- POPULAR ক্যাটাগরি -->
    <div class="category-action-chip active-node" onclick="filterCasinoGamesByCategory('popular', this)">
        <div class="chip-icon">🔥</div>
        <span class="chip-label">POPULAR</span>
    </div>

    <!-- BLSLOTS ক্যাটাগরি -->
    <div class="category-action-chip" onclick="filterCasinoGamesByCategory('blslots', this)">
        <div class="chip-icon">🎰</div>
        <span class="chip-label">BLSLOTS</span>
    </div>

    <!-- BLLOTTERY ক্যাটাগরি -->
    <div class="category-action-chip" onclick="filterCasinoGamesByCategory('bllottery', this)">
        <div class="chip-icon">🎫</div>
        <span class="chip-label">BLLOTTERY</span>
    </div>

    <!-- BLGAMES ক্যাটাগরি -->
    <div class="category-action-chip" onclick="filterCasinoGamesByCategory('blgames', this)">
        <div class="chip-icon">🎮</div>
        <span class="chip-label">BLGAMES</span>
    </div>

    <!-- BLLIVE ক্যাটাগরি -->
    <div class="category-action-chip" onclick="filterCasinoGamesByCategory('bllive', this)">
        <div class="chip-icon">📺</div>
        <span class="chip-label">BLLIVE</span>
    </div>

    <!-- SLOTS ক্যাটাগরি -->
    <div class="category-action-chip" onclick="filterCasinoGamesByCategory('slots', this)">
        <div class="chip-icon">🕹️</div>
        <span class="chip-label">SLOTS</span>
    </div>

    <!-- FISHING ক্যাটাগরি -->
    <div class="category-action-chip" onclick="filterCasinoGamesByCategory('fishing', this)">
        <div class="chip-icon">🐟</div>
        <span class="chip-label">FISHING</span>
    </div>

    <!-- ORIGINAL ক্যাটাগরি -->
    <div class="category-action-chip" onclick="filterCasinoGamesByCategory('original', this)">
        <div class="chip-icon">👑</div>
        <span class="chip-label">ORIGINAL</span>
    </div>
    
</div>

<!-- 🧠 ডাইনামিক গেম ফিল্টারিং জাভাস্ক্রিপ্ট ইঞ্জিন ভাই ভাই -->
<script>
    function filterCasinoGamesByCategory(categoryName, element) {
        // ১. বাটন একটিভ স্টেট গ্লো রিয়েল-টাইম অদলবদল মেকানিজম
        document.querySelectorAll('.category-action-chip').forEach(chip => {
            chip.classList.remove('active-node');
        });
        element.classList.add('active-node');

        // ২. ডাইনামিক গেম কার্ড ফিল্টারিং কন্টেইনার লুপ
        // (আপনার ৪৪, ৫০, ৫৬ নম্বর লাইনের "game-card" ক্লাসগুলোর সাথে "slots", "fishing" ইত্যাদি ক্লাস যুক্ত করলে অটো ফিল্টার হবে ওস্তাদ)
        document.querySelectorAll('.game-card').forEach(gameCard => {
            if (categoryName === 'popular') {
                gameCard.style.display = 'block'; // পপুলার মোডে প্রাথমিক সব গেম ওপেন চেইন ভাই ভাই
            } else {
                if (gameCard.classList.contains(categoryName)) {
                    gameCard.style.display = 'block'; // ম্যাচিং গেম কার্ড ইনস্ট্যান্ট ওপেন
                } else {
                    gameCard.style.display = 'none'; // বাকি সব ওয়ান-শটে হাইд
                }
            }
        });
    }
</script>

    <!-- 🎰 আপনার ডায়েরির সম্পূর্ণ ২ পাতা মিলিয়ে ৩০ মেগা গেম গ্রিড বর্ম ভাই ভাই -->
    <div class="game-grid">

        <!-- ১. SUPER-ACE (সুপার টেক্কা স্লট) -->
        <div class="game-card" onclick="window.location.href='play.php?game=superace';">
            <img src="super-ace.png" alt="Super Ace">
            <p>1. SUPER-ACE</p>
        </div>

        <!-- ২. LUDO (লুডু লটারি বাজি) -->
        <div class="game-card" onclick="window.location.href='play.php?game=ludo7';">
            <img src="ludo-7.png" alt="Ludo">
            <p>2. LUDO 777</p>
        </div>

        <!-- ৩. LUCKY 777-SLOT (লাকি ৭৭৭ স্লট মাস্টার) -->
        <div class="game-card" onclick="window.location.href='play.php?game=slotmaster';">
            <img src="lucky-777.png" alt="Lucky 777">
            <p>3. LUCKY 777-SLOT</p>
        </div>

        <!-- ৪. HEAD-AND-TAIL (কয়েন ফ্লিপ হেড টেইল) -->
        <div class="game-card" onclick="window.location.href='play.php?game=coinflip';">
            <img src="coin-flip.png" alt="Head and Tail">
            <p>4. HEAD-AND-TAIL</p>
        </div>

        <!-- ৫. LUCKY-MINES (লাকি ল্যান্ডমাইন ক্র্যাশ) -->
        <div class="game-card" onclick="window.location.href='play.php?game=mines';">
            <img src="mines.png" alt="Lucky Mines">
            <p>5. LUCKY-MINES</p>
        </div>

        <!-- ৬. MEGA-WHEEL (মেগা ফরচুন চরকি স্পিন) -->
        <div class="game-card" onclick="window.location.href='play.php?game=megawheel';">
            <img src="mega-wheel.png" alt="Mega Wheel">
            <p>6. MEGA-WHEEL</p>
        </div>

        <!-- ७. BILLIONAIRE-SLOT (বিলিয়নেয়ার লাক স্লট) -->
        <div class="game-card" onclick="window.location.href='play.php?game=billionaireslot';">
            <img src="billionaire-slot.png" alt="Billionaire Slot">
            <p>7. BILLIONAIRE-SLOT</p>
        </div>

        <!-- ৮. ANDAR-BAHAR (অন্দর বাহার ক্যাসিনো) -->
        <div class="game-card" onclick="window.location.href='play.php?game=andarbahar';">
            <img src="andar-bahar.png" alt="Andar Bahar">
            <p>8. ANDAR-BAHAR</p>
        </div>

        <!-- ৯. BACCARAT-MASTER (ব্যাকারাত ভিআইপি ক্যাসিনো) -->
        <div class="game-card" onclick="window.location.href='play.php?game=baccarat';">
            <img src="baccarat.png" alt="Baccarat Master">
            <p>9. BACCARAT-MASTER</p>
        </div>

        <!-- ১০. BLACKJACK-21 (ব্ল্যাকজ্যাক ২১ ভিআইপি কার্ড) -->
        <div class="game-card" onclick="window.location.href='play.php?game=blackjack';">
            <img src="blackjack.png" alt="Blackjack 21">
            <p>10. BLACKJACK-21</p>
        </div>

        <!-- ১১. CRYPTO-MULTIPLY (ক্রিপ্টো মাল্টিপ্লায়ার বাস্ট) -->
        <div class="game-card" onclick="window.location.href='play.php?game=cryptomultiply';">
            <img src="crypto-multiply.png" alt="Crypto Multiply">
            <p>11. CRYPTO-MULTIPLY</p>
        </div>

        <!-- ১২. DICE-3D ( লাকি ছক্কা রোলার ৩ডি) -->
        <div class="game-card" onclick="window.location.href='play.php?game=dice3d';">
            <img src="dice-3d.png" alt="Dice 3D">
            <p>12. DICE-3D</p>
        </div>

        <!-- ১৩. DRAGON-TIGER (ড্রাগন টাইগার ওয়ান ক্লিক ডিল) -->
        <div class="game-card" onclick="window.location.href='play.php?game=dragontiger';">
            <img src="dragon-tiger.png" alt="Dragon Tiger">
            <p>13. DRAGON-TIGER</p>
        </div>

        <!-- ১৪. GOLDEN-FORTUNE-WIN (ধন-দেবতার ৩-রিল স্লট) -->
        <div class="game-card" onclick="window.location.href='play.php?game=fortunegod';">
            <img src="fortune-god.png" alt="Golden Fortune Win">
            <p>14. GOLDEN-FORTUNE-WIN</p>
        </div>

        <!-- ১৫. HILO-CARD (হাই-লো তাসের প্রেডিকশন) -->
        <div class="game-card" onclick="window.location.href='play.php?game=hilo';">
            <img src="hilo-card.png" alt="HiLo Card">
            <p>15. HILO-CARD</p>
        </div>

        <!-- ১৬. JHANDI-MUNDA (ঝান্ডি মুন্ডা / জোলোমি এশিয়ান ছক্কা) -->
        <div class="game-card" onclick="window.location.href='play.php?game=jhandimunda';">
            <img src="jhandi-munda.png" alt="Jhandi Munda">
            <p>16. JHANDI-MUNDA</p>
        </div>

        <!-- ১৭. LUCKY-MARBLE-POP (বাংলাদেশের ঐতিহ্যবাহী মার্বেল লটারি) -->
        <div class="game-card" onclick="window.location.href='play.php?game=marblepop';">
            <img src="marble-pop.png" alt="Lucky Marble Pop">
            <p>17. LUCKY-MARBLE-POP</p>
        </div>

        <!-- ১৮. LUCKY-MONEY-TREE (सौभाग्य টাকার গাছ স্লট) -->
        <div class="game-card" onclick="window.location.href='play.php?game=moneytree';">
            <img src="money-tree.png" alt="Lucky Money Tree">
            <p>18. LUCKY-MONEY-TREE</p>
        </div>

        <!-- ১৯. TWIST-SPIN (স্টেক স্টাইল ৩-রিং টুইস্ট মাল্টিপ্লায়ার) -->
        <div class="game-card" onclick="window.location.href='play.php?game=twistspin';">
            <img src="twist-spin.png" alt="Twist Spin">
            <p>19. TWIST-SPIN</p>
        </div>

        <!-- ২০. MINI-ROULETTE (মিনি ক্যাসিনো রুলেট চাকা) -->
        <div class="game-card" onclick="window.location.href='play.php?game=miniroulette';">
            <img src="mini-roulette.png" alt="Mini Roulette">
            <p>20. MINI-ROULETTE</p>
        </div>

        <!-- ২১. MONEY-COMING (মানি কামিং JILI ক্যাশ স্লট) -->
        <div class="game-card" onclick="window.location.href='play.php?game=moneycoming';">
            <img src="money-coming.png" alt="Money Coming">
            <p>21. MONEY-COMING</p>
        </div>

        <!-- ২২. RAINBOW-SLOT (রয়্যাল রেনবো ৭-কালার স্লট মেশিন) -->
        <div class="game-card" onclick="window.location.href='play.php?game=rainbowslot';">
            <img src="rainbow-slot.png" alt="Rainbow Slot">
            <p>22. RAINBOW-SLOT</p>
        </div>

        <!-- ২৩. ROYAL-DERBY (রয়্যাল ডার্বি থ্রিল ঘোড়দৌড় বাজি) -->
        <div class="game-card" onclick="window.location.href='play.php?game=royalderby';">
            <img src="royal-derby.png" alt="Royal Derby">
            <p>23. ROYAL-DERBY</p>
        </div>

        <!-- ২৪. ROYAL-KENO (রয়েল কেনো লটারি নাম্বার বেটিং) -->
        <div class="game-card" onclick="window.location.href='play.php?game=keno';">
            <img src="keno.png" alt="Royal Keno">
            <p>24. ROYAL-KENO</p>
        </div>

        <!-- ২৫. ROYAL-PLINKO (স্টেক স্টাইল ওরিজিনাল প্লিনকো বল ড্রপ) -->
        <div class="game-card" onclick="window.location.href='play.php?game=plinko';">
            <img src="plinko.png" alt="Royal Plinko">
            <p>25. ROYAL-PLINKO</p>
        </div>

        <!-- ২৬. SUPER-ACE-SLOT (সুপার টেক্কা ৪x৫ মেগা স্লট মেশিন) -->
        <div class="game-card" onclick="window.location.href='play.php?game=superaceslot';">
            <img src="super-ace-slot.png" alt="Super Ace Slot">
            <p>26. SUPER-ACE-SLOT</p>
        </div>

        <!-- ২৭. TEEN-PATTI (তিন পাত্তি এশিয়ান রাজকীয় শোডাউন) -->
        <div class="game-card" onclick="window.location.href='play.php?game=teenpatti';">
            <img src="teen-patti.png" alt="Teen Patti">
            <p>27. TEEN-PATTI</p>
        </div>

        <!-- ২৮. TWIST-MULTIPLE-SPIN (৩-লেয়ার টুইস্ট মাল্টিপ্লায়ার হুইল) -->
        <div class="game-card" onclick="window.location.href='play.php?game=twistmultiplespin';">
            <img src="twist-multiple-spin.png" alt="Twist Multiple Spin">
            <p>28. TWIST-MULTIPLE-SPIN</p>
        </div>

        <!-- ২৯. AVIATOR 2.0 (এভিয়েটর ২.০ নিউ আল্ট্রা ক্র্যাশ গেম) -->
        <div class="game-card" onclick="window.location.href='play.php?id=aviator2';">
            <img src="aviator-2.png" alt="Aviator 2.0">
            <p>29. AVIATOR 2.0</p>
        </div>

        <!-- ৩০. Color-Trade (কালার ট্রেড উইনগো লটারি চাবি!) -->
        <div class="game-card" onclick="window.location.href='play.php?game=color';">
            <img src="color-trade.png" alt="Color Trade">
            <p>30. Color-Trade</p>
        </div>

    </div> <!-- 🚀 টোটাল ৩০ মেগা গেম গ্রিড ক্লোজিং টাইট বর্ম সিলড ভাই ভাই -->


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
/* নোটিশ কনটেইনার স্টাইল */
marquee {
    display: block;
    background: #000;
    color: #00ff88;
    padding: 10px 0;
    font-size: 13px;
    font-weight: bold;
    border-bottom: 1px solid #111;
    position: relative;
    z-index: 999 !important;
}
    
.slider-container {
    width: 100%;
    height: 130px;
    margin-top: 5px !important;
    margin-bottom: 10px;
    border-radius: 0;
    overflow: hidden;
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
    gap: 8px;           /* কার্ডগুলোর মাঝখানের গ্যাপ কমানো হয়েছে */
    padding: 10px;
}
.game-card {
    background: #111;
    border-radius: 10px;
    overflow: hidden;
    height: 130px;      /* কার্ডের মোট দৈর্ঘ্য কমিয়ে ফিক্সড করা হলো */
    border: 1px solid #222;
    text-align: center;
}
.game-card:active { transform: scale(0.95); }
.game-card img {
    width: 100%;
    height: 90px;       /* ছবির উচ্চতা কমিয়ে দেওয়া হলো যাতে কার্ড বেশি লম্বা না লাগে */
    object-fit: cover;
}

.game-card p {
    font-size: 9px;     /* টেক্সট ছোট করা হয়েছে */
    margin: 5px 0;
    color: #888;
    font-weight: bold;
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
/* 🎡 [গ্লোবাল ওয়ান-শটে পাশাপাশি সোজা করার কিংস বর্ম] */
.lobby-category-scroll-bar {
    width: 100% !important;
    display: flex !important; /* উপর-নিচে জ্যাম ভেঙে ওয়ান-শটে পাশাপাশি সাজানোর চাবি ভাই */
    flex-direction: row !important; /* প্রতিটা বাটনকে ডানে ডানে সোজা রাখবে */
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 20px !important; /* প্রতিটা বাটনের মাঝখানের পারফেক্ট গ্যাপ */
    padding: 12px 14px !important;
    background: #111111 !important; /* আপনার মেইন ডার্ক থিমের সাথে ওরিজিনাল ব্যাকগ্রাউন্ড সিঙ্ক */
    border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    overflow-x: auto !important; /* মোবাইল স্ক্রিনে ডানে-বামে স্মুথ সুইপ স্ক্রলিং সচল লক */
    white-space: nowrap !important; /* কোনো টেক্সট বা বাটন কখনোই নিচে ভাঙবে না ভাই ভাই */
    box-sizing: border-box !important;
    -webkit-overflow-scrolling: touch !important;
}

/* 📱 প্রতিটি ক্যাটাগরি চিপ বোতাম ফ্রেম পাশাপাশি এলাইনমেন্ট লক */
.category-action-chip {
    display: inline-flex !important; /* ব্লক জ্যাম চিরতরে ভ্যানিশ করার ট্রিক */
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 75px !important; /* বড় টেক্সটগুলো এক লাইনে রাখার সেফ সাইজ */
    cursor: pointer !important;
    flex-shrink: 0 !important; /* বাজি চাপলেও বাটন কখনো দুমড়ে মুচড়ে ছোট হবে না ভাই ভাই */
}

/* আইকন বক্স কন্ট্রোল */
.chip-icon {
    font-size: 22px !important;
    margin-bottom: 4px !important;
    display: block !important;
}

/* নিচে ক্যাটাগরি টেক্সট লেবেল (POPULAR, BLSLOTS ইত্যাদি) */
.chip-label {
    font-size: 11px !important;
    font-weight: 800 !important;
    color: #9ca3af !important;
    text-align: center !important;
    letter-spacing: 0.5px !important;
    display: block !important;
}

/* 🚨 ক্যাশ মেমোরি বিউটি ফিক্স: ভেতরের কুৎসিত ওল্ড স্ক্রলবার হাইড করার চাবি */
.lobby-category-scroll-bar::-webkit-scrollbar {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
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
