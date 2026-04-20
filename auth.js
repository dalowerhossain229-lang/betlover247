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
