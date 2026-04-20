function openLogin() { document.getElementById('loginModal').style.display = 'flex'; }
function closeAll() { document.getElementById('loginModal').style.display = 'none'; }

function processLogin() {
    const user = document.getElementById('loginUser').value;
    const pass = document.getElementById('loginPass').value;
    
    let fd = new FormData();
    fd.append('username', user);
    fd.append('password', pass);

    fetch('login_proc.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') { location.reload(); }
        else { alert(data.message); }
    });
}
function openRegister() { document.getElementById('regModal').style.display = 'flex'; }

function processRegister() {
    const name = document.getElementById('regName').value;
    const user = document.getElementById('regUser').value;
    const pass = document.getElementById('regPass').value;
    
    let fd = new FormData();
    fd.append('fullName', name);
    fd.append('username', user);
    fd.append('password', pass);

    fetch('register.php', { method: 'POST', body: fd })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if(data.status === 'success') { closeAll(); openLogin(); }
    });
}
// রেজিস্ট্রেশন পপ-আপ ওপেন করা
function openRegister() {
    const regModal = document.getElementById('regModal');
    if(regModal) regModal.style.display = 'flex';
}

// অফার পেজে নিয়ে যাওয়া
function openOffer() {
    window.location.href = 'offer.php';
}
