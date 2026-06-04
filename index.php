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
          // 🎰 [ডাইনামিক গেম ফিল্টারিং রাজকীয় ১০০% নিখুঁত মাস্টার স্ক্রিপ্ট ভাই ভাই]
        function filterCasinoGamesByCategory(categoryName, element) {
            // ১. কাস্টম একটিভ চিপস স্টেট রি-ইউনিক রিসেট বাউন্সার লক
            document.querySelectorAll('.category-action-chip').forEach(chip => {
                chip.classList.remove('active-node');
            });
            if (element) element.classList.add('active-node');

            // ২. ডাইনামিক গেম কার্ড ফিল্টারিং কন্টেইনার পাইপ라인
            // লবির সব গেম কার্ড লুপের মাধ্যমে এক মিলি-সেকেন্ডে রিড কমপ্লিট হবে ওস্তাদ
            document.querySelectorAll('.game-card').forEach(gameCard => {
                
                if (categoryName === 'popular') {
                    // 🔒 [পপুলার জ্যাম ব্লকার বর্ম]: সবগুলো গেম একসাথে খোলার ওল্ড ট্র্যাপ সাফ!
                    // শুধুমাত্র যে গেমগুলোর বডিতে 'popular' ক্লাস বা ডাটা-ট্যাগ লক করা আছে, তারাই শুধু এখানে শো করবে ওস্তাদ!
                    if (gameCard.classList.contains('popular')) {
                        gameCard.style.display = 'block';
                    } else {
                        gameCard.style.display = 'none';
                    }
                } else {
                    // ৩. অন্যান্য নির্দিষ্ট ক্যাটাগরি (blslots, bllottery, slots, fishing, bllive, blgames) পারফেক্টলি এলাইনমেন্ট ম্যাচিং লক
                    if (gameCard.classList.contains(categoryName)) {
                        gameCard.style.display = 'block';
                    } else {
                        gameCard.style.display = 'none';
                    }
                }
            });
        }

        // 🛫 [২-লেয়ার ডাইনামিক কম্বো এন্ট্রি হুক - পিএইচপি সেশন আইডি ইন্টিগ্রেশন বর্ম ওস্তাদ ভাই ভাই!]
        function launchCasinoGameViaPlayPage(gameFolderKeyName) {
            const constUrlParams = new URLSearchParams(window.location.search);
            
            // ১. লিঙ্ক থেকে প্রথমে আইডি ক্যাচ করার ট্রাই
            let constUserId = constUrlParams.get('userId') || constUrlParams.get('id') || constUrlParams.get('username') || "";
            
            // 🔒 [গ্র্যান্ড কিংস ফিক্সড ট্রিক]: লিঙ্ক ফাক্কা থাকলে সরাসরি পিএইচপি সেশনের আসল লগইন ইউজার আইডি এখানে ফিক্সড লক হবে!
            // এর ফলে নোড সার্ভার আর ডাটাবেজ ওয়ান-শটে আসল প্লেয়ারকে চিনে এক মিলি-সেকেন্ডে ওরিজিনাল অ্যাকাউন্ট ব্যালেন্স লোড করে দেবে!
            if (!constUserId || constUserId === "guest_user" || constUserId === "undefined" || constUserId === "") {
                constUserId = "<?php echo isset($u) ? $u : ($_SESSION['user_id'] ?? $_SESSION['username'] ?? ''); ?>"; 
            }

            // যদি কোনো কারণে সেশনও খালি পায়, তবে ফলব্যাক ডিফল্ট ইউজার ট্র্যাক লক
            if (!constUserId || constUserId === "") {
                constUserId = "guest_user";
            }

            const constWallet = constUrlParams.get('wallet') || "main";

            // 🛫 ওরিজিনাল play.php পাতায় আসল ইউজার আইডি নিয়ে গ্র্যান্ড রিডাইরেক্ট
            window.location.href = `play.php?game=${gameFolderKeyName}&userId=${encodeURIComponent(constUserId)}&wallet=${constWallet}`;
        }


  
</script>

     <!-- 🏟️ [BETLOVER777 / DKWIN ওরিজিনাল ক্যাটাগরি ভিত্তিক গেমকার্ড ম্যাট্রিক্স গ্রিড বর্ম ভাই ভাই] -->
    <div class="game-grid">

        <!-- 🔴 ক্যাটাগরি ১: POPULAR গেমস (সেরা পপুলার ওরিজিনাল ট্রেন্ডিং ইউনিক গেমগুলো ওস্তাদ!) -->
        <div class="game-card popular blslots slots" onclick="launchCasinoGameViaPlayPage('superace')">
            <img src="super-ace.png" alt="Super Ace">
            <div class="game-card-title-text">SUPER ACE</div>
        </div>

        <div class="game-card popular blgames original" onclick="launchCasinoGameViaPlayPage('ludo')">
            <img src="ludo777.png" alt="Ludu 777">
            <div class="game-card-title-text">LUDU777</div>
        </div>

        <div class="game-card popular bllottery" onclick="launchCasinoGameViaPlayPage('color')">
            <img src="color-trade.png" alt="Color Trade">
            <div class="game-card-title-text">COLOR-TRADE</div>
        </div>

        <div class="game-card popular blgames" onclick="launchCasinoGameViaPlayPage('aviator')">
            <img src="aviator.png" alt="Aviator 2.0">
            <div class="game-card-title-text">AVIATOR 2.0</div>
        </div>

        <!-- 🔵 ক্যাটাগরি ২: BLGAMES / ORIGINAL ক্যাসিনো গেমস (আপনার স্ক্রিনশটের হুবহু ওরিজিনাল ওッズ সিঙ্ক!) -->
        <div class="game-card blgames original" onclick="launchCasinoGameViaPlayPage('chicken')">
            <img src="chicken-road.png" alt="Chicken Road">
            <div class="game-card-title-text">CHICKEN ROAD</div>
        </div>

        <div class="game-card blgames original" onclick="launchCasinoGameViaPlayPage('mines')">
            <img src="lucky-mines.png" alt="Lucky Mines">
            <div class="game-card-title-text">LUCKY MINES</div>
        </div>

        <div class="game-card blgames original" onclick="launchCasinoGameViaPlayPage('andarbahar')">
            <img src="andar-bahar.png" alt="Andar Bahar">
            <div class="game-card-title-text">ANDAR-BAHAR</div>
        </div>

        <div class="game-card blgames cryptomultiply" onclick="launchCasinoGameViaPlayPage('cryptomultiply')">
            <img src="crypto-multiply.png" alt="Crypto Multiply">
            <div class="game-card-title-text">CRYPTO-MULTIPLY</div>
        </div>

        <div class="game-card blgames dice3d" onclick="launchCasinoGameViaPlayPage('dice3d')">
            <img src="dice-3d.png" alt="Dice 3D">
            <div class="game-card-title-text">DICE-3D</div>
        </div>

        <div class="game-card blgames dragontiger" onclick="launchCasinoGameViaPlayPage('dragontiger')">
            <img src="dragon-tiger.png" alt="Dragon Tiger">
            <div class="game-card-title-text">DRAGON-TIGER</div>
        </div>

        <div class="game-card blgames" onclick="launchCasinoGameViaPlayPage('hilocard')">
            <img src="hilo-card.png" alt="Hilo Card">
            <div class="game-card-title-text">HILO-CARD</div>
        </div>

        <div class="game-card blgames" onclick="launchCasinoGameViaPlayPage('jhandimunda')">
            <img src="jhandi-munda.png" alt="Jhandi Munda">
            <div class="game-card-title-text">JHANDI-MUNDA</div>
        </div>

        <div class="game-card blgames" onclick="launchCasinoGameViaPlayPage('luckymarblepop')">
            <img src="lucky-marble-pop.png" alt="Lucky Marble Pop">
            <div class="game-card-title-text">LUCKY-MARBLE-POP</div>
        </div>

        <div class="game-card blgames" onclick="launchCasinoGameViaPlayPage('fantan')">
            <img src="fan-tan.png" alt="Fan Tan">
            <div class="game-card-title-text">FAN-TAN</div>
        </div>

        <div class="game-card blgames" onclick="launchCasinoGameViaPlayPage('sicbo')">
            <img src="sic-bo.png" alt="Sic Bo">
            <div class="game-card-title-text">SIC BO</div>
        </div>

        <div class="game-card blgames" onclick="launchCasinoGameViaPlayPage('penalty-shootout')">
            <img src="penalty-shootout.png" alt="Penalty Shootout">
            <div class="game-card-title-text">PENALTY-SHOOTOUT</div>
        </div>

        <div class="game-card blgames bllive" onclick="launchCasinoGameViaPlayPage('baccaratmaster')">
            <img src="baccarat-master.png" alt="Baccarat Master">
            <div class="game-card-title-text">BACCARAT MASTER</div>
        </div>

        <div class="game-card blgames bllive" onclick="launchCasinoGameViaPlayPage('blackjack21')">
            <img src="blackjack-21-psch.png" alt="Blackjack 21">
            <div class="game-card-title-text">BLACKJACK-21</div>
        </div>

        <!-- 🟢 ক্যাটাগরি ৩: FISHING গেমস (সরাসরি ফিশিং সেকশনে স্মুথলি এন্ট্রি লক) -->
        <div class="game-card fishing" onclick="launchCasinoGameViaPlayPage('dragon ball fishing')">
            <img src="dragon-ball-fishing.png" alt="Dragon Ball Fishing">
            <div class="game-card-title-text">DRAGON-BALL-FISHING</div>
        </div>

        <div class="game-card fishing" onclick="launchCasinoGameViaPlayPage('betlover24fishing')">
            <img src="betlover24-fishing.png" alt="Betlover24 Fishing">
            <div class="game-card-title-text">BETLOVER-24-FISHING</div>
        </div>

        <div class="game-card blgames" onclick="launchCasinoGameViaPlayPage('fightercrash')">
            <img src="fighter-crash.png" alt="Fighter Crash">
            <div class="game-card-title-text">FIGHTER-CRASH</div>
        </div>

        <!-- 🟡 ক্যাটাগরি ৪: BLSLOTS / SLOTS গেমস (স্লটস চাদর গ্রিড এলাইনমেন্ট লক ওস্তাদ!) -->
        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('moneycoming')">
            <img src="money-coming.png" alt="Money Coming">
            <div class="game-card-title-text">MONEY-COMING</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('royalderby')">
            <img src="royal-derby.png" alt="Royal Derby">
            <div class="game-card-title-text">ROYAL-DERBY</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('billionaireslot')">
            <img src="billionaire-slot.png" alt="Billionaire Slot">
            <div class="game-card-title-text">BILLIONAIRE-SLOT</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('moneytree')">
            <img src="money-tree.png" alt="Money Tree">
            <div class="game-card-title-text">MONEY TREE</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('crazy777')">
            <img src="crazy-777.png" alt="Crazy 777">
            <div class="game-card-title-text">CRAZY-777</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('alibaba')">
            <img src="alibaba.png" alt="Alibaba">
            <div class="game-card-title-text">ALIBABA</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('fortunegems')">
            <img src="fortune-gems.png" alt="Fortune Gems">
            <div class="game-card-title-text">FORTUNE GEMS</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('caishendao')">
            <img src="cai-shen-dao.png" alt="Cai Shen Dao">
            <div class="game-card-title-text">CAI SHEN DAO</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('fafafa')">
            <img src="fa-fa-fa.png" alt="Fa Fa Fa">
            <div class="game-card-title-text">FA-FA-FA</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('mythicalphoenix')">
            <img src="mythical-phoenix.png" alt="Mythical Phoenix">
            <div class="game-card-title-text">MYTHICAL PHOENIX</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('goldenchicken')">
            <img src="golden-chicken.png" alt="Golden Chicken">
            <div class="game-card-title-text">GOLDEN CHICKEN</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('shanghainights')">
            <img src="shanghai-nights.png" alt="Shanghai Nights">
            <div class="game-card-title-text">SHANGHAI-NIGHTS</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('jadeelephant')">
            <img src="jade-elephant.png" alt="Jade Elephant">
            <div class="game-card-title-text">JADE ELEPHANT</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('dragonboat')">
            <img src="dragon-boat.png" alt="Dragon Boat">
            <div class="game-card-title-text">DRAGON-BOAT</div>
        </div>

        <div class="game-card blslots slots" onclick="launchCasinoGameViaPlayPage('mahjongways')">
            <img src="mahjong-way.png" alt="Mahjong Way">
            <div class="game-card-title-text">MAHJONG-WAY</div>
        </div>

        <!-- 🔴 ক্যাটাগরি ৫: BLLOTTERY / লটারি গেমস কন্টিনিউড -->
        <div class="game-card bllottery" onclick="launchCasinoGameViaPlayPage('royalkeno')">
            <img src="royal-keno.png" alt="Royal Keno">
            <div class="game-card-title-text">ROYAL-KENO</div>
        </div>

        <div class="game-card bllottery" onclick="launchCasinoGameViaPlayPage('royalplinko')">
            <img src="royal-plinko.png" alt="Royal Plinko">
            <div class="game-card-title-text">ROYAL-PLINKO</div>
        </div>

        <div class="game-card bllottery" onclick="launchCasinoGameViaPlayPage('luckywheel')">
            <img src="mega-wheel.png" alt="Lucky Wheel">
            <div class="game-card-title-text">LUCKY-WHEEL</div>
        </div>

                       <!-- 🔴 ক্যাটাগরি ৫: BLLOTTERY / লটারি গেমস (সরাসরি লটারি সেকশনে ১০০% একুরেট এন্ট্রি লক!) -->
        <div class="game-card bllottery" onclick="launchCasinoGameViaPlayPage('luckywheel')">
            <img src="mega-wheel.png" alt="Lucky Wheel">
            <div class="game-card-title-text">LUCKY-WHEEL</div>
        </div>

        <div class="game-card bllottery" onclick="launchCasinoGameViaPlayPage('royalkeno')">
            <img src="royal-keno.png" alt="Royal Keno">
            <div class="game-card-title-text">ROYAL-KENO</div>
        </div>

        <div class="game-card bllottery" onclick="launchCasinoGameViaPlayPage('royalplinko')">
            <img src="royal-plinko.png" alt="Royal Plinko">
            <div class="game-card-title-text">ROYAL-PLINKO</div>
        </div>

        <!-- 🟢 ক্যাটাগরি ৬: BLLIVE / লাইভ ক্যাসিনো গেমস (সরাসরি লাইভ সেকশনে মাখনের মতো এলাইনমেন্ট লক) -->
        <div class="game-card bllive" onclick="launchCasinoGameViaPlayPage('teenpatti')">
            <img src="teen-patti.png" alt="Teen Patti">
            <div class="game-card-title-text">TEEN PATTI</div>
        </div>

        <div class="game-card bllive" onclick="launchCasinoGameViaPlayPage('megawheel')">
            <img src="mega-wheel.png" alt="Mega Wheel">
            <div class="game-card-title-text">MEGA-WHEEL</div>
        </div>

        <div class="game-card bllive" onclick="launchCasinoGameViaPlayPage('miniroulette')">
            <img src="mini-roulette.png" alt="Mini Roulette">
            <div class="game-card-title-text">MINI-ROULETTE</div>
        </div>

        <div class="game-card bllive" onclick="launchCasinoGameViaPlayPage('lightningroulette')">
            <img src="lightning-roulette.png" alt="Lightning Roulette">
            <div class="game-card-title-text">LIGHTNING-ROULETTE</div>
        </div>

        <div class="game-card bllive" onclick="launchCasinoGameViaPlayPage('fantan')">
            <img src="fan-tan.png" alt="Fan Tan">
            <div class="game-card-title-text">FAN-TAN</div>
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

.game-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;           
    padding: 10px;
    background-color: #ffffff !important; /* পুরো গ্রিড সাদা থাকবে */
}
.game-card {
    background: #111111 !important; /* আপনার স্ক্রিনশট অনুযায়ী কার্ডের ব্যাকগ্রাউন্ড কালোই রাখা হলো */
    border-radius: 10px;
    overflow: hidden;
    height: 130px;      
    border: 1px solid #222; 
    text-align: center;
}
.game-card:active { transform: scale(0.95); }
.game-card img {
    width: 100%;
    height: 90px;      
    object-fit: cover;
}

/* গেমের সব নামের জন্য ১০০% কার্যকরী রেইনবো কালার ফিক্স */
.game-grid .game-card p,
.game-card p,
.game-card span,
.game-card h4 {
    font-size: 9px !important;    
    margin: 5px 0 !important;
    font-weight: 900 !important; /* লেখাটি আরও ফুটিয়ে তোলার জন্য বোল্ড করা হলো */
    display: block !important;
    width: 100% !important;
    text-align: center !important;

    /* রেইনবো ব্যাকগ্রাউন্ড গ্রিডিয়েন্ট */
    background: linear-gradient(to right, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #9400d3) !important;
    
    /* লেখা টেক্সটে ক্লিপ করার আধুনিক নিয়ম */
    -webkit-background-clip: text !important;
    background-clip: text !important;
    
    /* ভেতরের ওল্ড হোয়াইট কালার ফোর্সবলি ভ্যানিশ করার আসল চাবি */
    -webkit-text-fill-color: transparent !important;
    text-fill-color: transparent !important;
    color: transparent !important; 
    
    /* অ্যানিমেশন স্পিড সামান্য বাড়িয়ে ৪ সেকেন্ড করা হলো যাতে কালার পরিবর্তন চোখে পড়ে */
    background-size: 200% auto !important;
    animation: rainbow-animation 4s linear infinite !important;
}

/* রেইনবো অ্যানিমেশনের সঠিক নিয়ম */
@keyframes rainbow-animation {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

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
    white-space: nowrap !important; /* কোনো টেক্সট বা বাটন টাকায় নিচে ভাঙবে না ভাই ভাই */
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
