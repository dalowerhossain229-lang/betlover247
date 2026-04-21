<?php include 'header.php'; ?>
<div style="padding: 20px; text-align: center; max-width: 400px; margin: auto;">
    <h2 style="color:var(--neon);">DEPOSIT MONEY</h2>
    
    <!-- নম্বর কার্ড -->
    <div style="background:#161b22; padding:15px; border-radius:12px; border:1px solid var(--neon); margin-bottom:20px;">
        <p style="color:#888; font-size:12px; margin:0;">বিকাশ/নগদ এজেন্ট (Cash Out)</p>
        <h2 style="color:var(--gold); margin:5px 0;">017XXXXXXXX</h2>
        <span style="background:var(--neon); color:#000; padding:2px 10px; border-radius:10px; font-size:10px; font-weight:900;">AGENT</span>
    </div>

    <input type="number" id="d_amount" class="modal-input" placeholder="টাকার পরিমাণ (Minimum 200)">
    <input type="text" id="d_trx" class="modal-input" placeholder="TrxID (ট্রানজেকশন আইডি)">
    
    <button class="btn-auth-submit" onclick="sendDeposit()">SUBMIT REQUEST</button>
</div>


<?php include 'footer.php'; ?>
