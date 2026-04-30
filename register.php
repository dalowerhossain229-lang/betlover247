<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | BetLover</title>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reg-box { background: #111; padding: 30px; border-radius: 15px; border: 1px solid #333; width: 100%; max-width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #222; border: 1px solid #444; color: #fff; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #ffdf1b; border: none; color: #000; font-weight: bold; border-radius: 8px; cursor: pointer; margin-top: 10px; }
        #msg { margin-top: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="reg-box">
    <h2 style="color: #ffdf1b;">CREATE ACCOUNT</h2>
    <form id="regForm">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        
        <!-- ৫% কমিশনের জন্য এই রেফার কোড বক্সটি জরুরি -->
        <input type="text" name="ref_by" placeholder="Referral Code (Optional)" style="border: 1px solid #ffdf1b33;">
        
        <button type="submit">REGISTER NOW</button>
    </form>
    <div id="msg"></div>
</div>

<script>
document.getElementById('regForm').onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const res = await fetch('register_proc.php', { method: 'POST', body: formData });
    const data = await res.json();
    
    const msgDiv = document.getElementById('msg');
    msgDiv.innerHTML = data.message;
    msgDiv.style.color = data.status === 'success' ? '#00ff88' : '#ff4d4d';
    
    if(data.status === 'success') {
        setTimeout(() => window.location.href = 'profile.php', 2000);
    }
};
</script>

</body>
</html>
