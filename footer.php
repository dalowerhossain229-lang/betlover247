     <footer class="footer-nav">
        <a href="index.php" class="nav-tab">
            <i class="fa-solid fa-house"></i>
            <span>HOME</span>
        </a>
        
        <a href="sports.php" class="nav-tab">
            <i class="fa-solid fa-trophy"></i>
            <span>SPORTS</span>
        </a>
<div class="nav-tab promo-center" onclick="location.href='promo.php'">
    <i class="fa-solid fa-gift"></i>
    <span>PROMO</span>
</div>

        
        
        <a href="casino.php" class="nav-tab">
            <i class="fa-solid fa-clover"></i>
            <span>CASINO</span>
        </a>
        
        <a href="profile.php" class="nav-tab">
            <i class="fa-solid fa-user"></i>
            <span>ACCOUNT</span>
        </a>
    </footer>

    <style>
        .footer-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: linear-gradient(180deg, #073128 0%, #000 100%);
            display: flex;
            justify-content: space-around;
            align-items: center;
            border-top: 2px solid var(--neon);
            z-index: 5000;
            box-shadow: 0 -5px 20px rgba(0, 255, 136, 0.2);
        }

        .nav-tab {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .nav-tab i {
            font-size: 18px;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .nav-tab span {
            font-size: 9px;
            font-weight: 900;
            color: var(--gold); /* আপনার ড্রয়িং এর হলুদ কালার */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* মাঝখানের সেই বিশেষ PROMO বাটন ডিজাইন */
        .promo-center {
            background: var(--neon) !important; /* উজ্জ্বল নিওন গ্রিন */
            width: 65px !important;
            height: 65px !important;
            border-radius: 50% !important;
            margin-top: -35px !important;
            border: 5px solid var(--dark) !important;
            box-shadow: 0 0 15px var(--neon);
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .promo-center i {
            color: #000 !important; /* আইকন কালো হবে */
            font-size: 22px !important;
            margin-bottom: 2px !important;
        }

        .promo-center span {
            color: #000 !important; /* লেখাও কালো হবে */
            font-size: 9px !important;
            font-weight: 900 !important;
        }

        /* মাউস হোভার ইফেক্ট */
        .nav-tab:active {
            transform: scale(0.9);
        }
    </style>

    <!-- আইকন লাইব্রেরি নিশ্চিত করা -->
    <link rel="stylesheet" href="https://cloudflare.com">
   
