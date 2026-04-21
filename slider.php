<!-- Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com" />

<style>
    .slider-section { width: 100%; padding: 10px 0; background: #000; }
    .swiper-slide img {
        width: 100%; height: 160px; object-fit: cover;
        border-radius: 12px; border: 1.5px solid #00ff88;
    }
    .swiper-pagination-bullet { background: #00ff88 !important; }
</style>

<div class="slider-section">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php
            // ডাটাবেস থেকে ছবি আনার চেষ্টা
            $slider_res = false; 

            if ($slider_res && $slider_res->num_rows > 0) {
                while($slide = $slider_res->fetch_assoc()) {
                    echo '<div class="swiper-slide">
                            <a href="'.$slide['offer_link'].'">
                                <img src="assets/img/'.$slide['image_url'].'" alt="Slider">
                            </a>
                          </div>';
                }
            } else {
                // ডাটাবেসে ডাটা না থাকলে এই ৩টি ডামি স্লাইডার দেখাবে
                echo '<div class="swiper-slide"><img src="https://placeholder.com" alt="Slider"></div>';
                echo '<div class="swiper-slide"><img src="https://placeholder.com" alt="Slider"></div>';
                echo '<div class="swiper-slide"><img src="https://placeholder.com" alt="Slider"></div>';
            }
            ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>

<script src="https://unpkg.com"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: { delay: 3000 },
        pagination: { el: ".swiper-pagination", clickable: true },
    });
</script>
            
