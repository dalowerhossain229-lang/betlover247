<!-- Swiper CSS (স্লাইডার স্টাইলের জন্য) -->
<link rel="stylesheet" href="https://unpkg.com" />

<style>
    .slider-section {
        width: 100%;
        padding: 10px 0;
        background: var(--dark);
    }
    .swiper-slide img {
        width: 100%;
        height: 160px; /* আপনার পছন্দমতো উচ্চতা দিতে পারেন */
        object-fit: cover;
        border-radius: 12px;
        border: 1.5px solid rgba(0, 255, 136, 0.3);
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }
    .swiper-pagination-bullet {
        background: var(--neon) !important;
        opacity: 0.5;
    }
    .swiper-pagination-bullet-active {
        opacity: 1;
        width: 20px;
        border-radius: 5px;
    }
</style>

<div class="slider-section">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php
            // ডাটাবেস থেকে ১০টি একটিভ স্লাইডার নিয়ে আসা
            $slider_query = "SELECT * FROM sliders WHERE status = 'active' ORDER BY id DESC LIMIT 10";
            $slider_res = $conn->query($slider_query);

            if ($slider_res && $slider_res->num_rows > 0) {
                while($slide = $slider_res->fetch_assoc()) {
                    echo '<div class="swiper-slide">
                            <a href="'.$slide['offer_link'].'">
                                <img src="assets/img/'.$slide['image_url'].'" alt="BetLover777 Offer">
                            </a>
                          </div>';
                }
            } else {
                // ডাটাবেসে ছবি না থাকলে ডিফল্ট ডামি স্লাইডার (টেস্ট করার জন্য)
                for($i=1; $i<=3; $i++) {
                    echo '<div class="swiper-slide">
                            <img src="https://placeholder.com.'" alt="Placeholder">
                          </div>';
                }
            }
            ?>
        </div>
        <!-- নিচে ছোট ডটগুলো -->
        <div class="swiper-pagination"></div>
    </div>
</div>

<!-- Swiper JS (স্লাইডার সচল করার জন্য) -->
<script src="https://unpkg.com"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        effect: "slide", // আপনি চাইলে "fade" ও দিতে পারেন
    });
</script>
