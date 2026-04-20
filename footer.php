    <footer class="footer-nav">
        <div class="nav-tab" onclick="location.href='index.php'"><i class="fa-solid fa-house"></i><span>HOME</span></div>
        <div class="nav-tab" onclick="location.href='sports.php'"><i class="fa-solid fa-trophy"></i><span>SPORTS</span></div>
        <div class="nav-tab middle-btn" onclick="openPromo()"><i class="fa-solid fa-gift"></i></div>
        <div class="nav-tab" onclick="location.href='casino.php'"><i class="fa-solid fa-clover"></i><span>CASINO</span></div>
        <div class="nav-tab" onclick="location.href='profile.php'"><i class="fa-solid fa-user"></i><span>ACCOUNT</span></div>
    </footer>

    <style>
        .footer-nav { position: fixed; bottom: 0; width: 100%; height: 70px; background: #073128; display: flex; justify-content: space-around; align-items: center; border-top: 2px solid var(--neon); z-index: 5000; box-shadow: 0 -5px 15px rgba(0,0,0,0.5); }
        .nav-tab { flex: 1; display: flex; flex-direction: column; align-items: center; cursor: pointer; text-decoration: none; color: white; }
        .nav-tab i { font-size: 18px; color: white; margin-bottom: 4px; }
        .nav-tab span { font-size: 9px; font-weight: 900; color: var(--gold); }
        .middle-btn { background: var(--neon); width: 55px; height: 55px; border-radius: 50%; margin-top: -35px; border: 4px solid var(--dark); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px var(--neon); }
        .middle-btn i { color: black !important; font-size: 24px; }
    </style>
