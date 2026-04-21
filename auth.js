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
// ৩. রেজিস্ট্রেশন প্রসেস (অটো-লগইন সহ)
function processRegister() {
    const name = document.getElementById('regName').value;
    const user = document.getElementById('regUser').value;
    const pass = document.getElementById('regPass').value;
    
    if(!name || !user || !pass) { alert("সবগুলো তথ্য দিন!"); return; }

    let fd = new FormData();
    fd.append('fullName', name);
    fd.append('username', user);
    fd.append('password', pass);

    fetch('register.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') { 
            alert(data.message);
            location.reload(); // পেজ রিলোড হবে এবং ইউজারকে লগইন অবস্থায় দেখাবে
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
