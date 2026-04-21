// এডমিন যখন সেভ বাটনে চাপ দিবে
if(isset($_POST['update_offer'])) {
    $new_offer = mysqli_real_escape_string($conn, $_POST['offer_content']);
    $conn->query("UPDATE site_settings SET setting_value = '$new_offer' WHERE setting_key = 'offer_text'");
    echo "অফার আপডেট হয়েছে!";
}
