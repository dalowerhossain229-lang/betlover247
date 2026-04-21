// ১. মোডাল কন্ট্রোল ফাংশন
function openLogin() { 
    closeAll();
    document.getElementById('loginModal').style.display = 'flex'; 
}

function openRegister() { 
    closeAll();
    document.getElementById('regModal').style.display = 'flex'; 
}
function closeAll() { 
    if(document.getElementById('loginModal')) document.getElementById('loginModal').style.display = 'none';
    if(document.getElementById('regModal')) document.getElementById('regModal').style.display = 'none';
    if(document.getElementById('depModal')) document.getElementById('depModal').style.display = 'none';
    
    if(document.getElementById('withdrawModal')) document.getElementById('withdrawModal').style.display = 'none';
}



// ২. লগইন প্রসেস
function processLogin() {
    const user = document.getElementById('loginUser').value;
    const pass = document.getElementById('loginPass').value;
    
    if(!user || !pass) { alert("আইডি এবং পাসওয়ার্ড দিন!"); return; }

    let fd = new FormData();
    fd.append('username', user);
    fd.append('password', pass);

    fetch('login_proc.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') { 
            location.reload(); 
        } else { 
            alert(data.message); 
        }
    })
    .catch(() => alert("লগইন সার্ভার কানেকশন এরর!"));
}
function processRegister() {
    const user = document.getElementById('regUser').value;
    const pass = document.getElementById('regPass').value;
    
    if(!user || !pass) { alert("সবগুলো তথ্য দিন!"); return; }

    let fd = new FormData();
    fd.append('username', user);
    fd.append('password', pass);

    fetch('register.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') { 
            alert(data.message);
            location.reload(); 
        } else {
            alert(data.message);
        }
    })
    .catch(() => alert("রেজিস্ট্রেশন সার্ভার কানেকশন এরর!"));
}



// ৪. অন্যান্য লিঙ্ক
function openOffer() {
    window.location.href = 'offer.php';
}

function openPromo() {
    alert("নতুন প্রোমোশন অফার শীঘ্রই আসছে!");
}
function openDeposit() {
    closeAll();
    document.getElementById('depModal').style.display = 'flex';
}

function processDeposit() {
    const amount = document.getElementById('depAmount').value;
    const trx = document.getElementById('depTrx').value;

    if(!amount || !trx) { alert("সবগুলো তথ্য দিন!"); return; }

    let fd = new FormData();
    fd.append('amount', amount);
    fd.append('trx_id', trx);

    fetch('process_deposit.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') closeAll();
    })
    .catch(() => alert("সার্ভার এরর! process_deposit.php চেক করুন।"));
}
// উইথড্র কার্ড ওপেন করা
function openWithdraw() {
    closeAll();
    document.getElementById('withdrawModal').style.display = 'flex';
}

// উইথড্র রিকোয়েস্ট প্রসেস করা
function processWithdraw() {
    const amount = document.getElementById('wdAmount').value;
    const number = document.getElementById('wdNumber').value;
    const method = document.getElementById('wdMethod').value;

    if(!amount || !number) { alert("সবগুলো তথ্য দিন!"); return; }

    let fd = new FormData();
    fd.append('amount', amount);
    fd.append('number', number);
    fd.append('method', method);

    fetch('process_withdraw.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') closeAll();
    })
    .catch(() => alert("সার্ভার এরর! process_withdraw.php চেক করুন।"));
}
function showWalletMenu() {
    const menu = document.getElementById('walletMenu');
    menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'flex' : 'none';
}

// মেনুর বাইরে ক্লিক করলে মেনু বন্ধ হওয়া
window.onclick = function(event) {
    if (!event.target.matches('.balance-chip-top')) {
        const menu = document.getElementById('walletMenu');
        if (menu) menu.style.display = 'none';
    }
}
function handleLogout() {
    if(confirm("আপনি কি লগআউট করতে চান?")) {
        // ব্রাউজারের লোকাল ডাটা ক্লিয়ার করা
        localStorage.clear();
        sessionStorage.clear();
        
        // সরাসরি logout.php ফাইলে পাঠানো
        window.location.replace('logout.php');
    }
}
