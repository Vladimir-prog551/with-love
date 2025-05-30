<body>
    <!-- about start -->
    <div class="about container">
        <div class="about_left">
            <h1>Цветы — это любовь, застывшая в бутонах</h1>
            <p>"Ликорис" — цветочный магазин, где каждая композиция создается с любовью и вниманием к деталям. Соединяем
                свежесть премиальных цветов с искусством флористики, чтобы дарить вам неповторимые букеты.</p>
            <div class="about_btn">
                <a href="/?page=catalog">В каталог</a>
            </div>
        </div>
        <div class="about_right">
            <div class="slide active"><img src="assets/media/info/image_5.jpg" alt=""></div>
            <div class="slide"><img src="assets/media/info/image_6.jpg" alt=""></div>
            <div class="slide"><img src="assets/media/info/image_3.jpg" alt=""></div>
            <div class="slide"><img src="assets/media/info/image_4.jpg" alt=""></div>
        </div>
    </div>
    <!-- about end -->

    <!-- files start -->
    <div class="container">
        <div class="download-section container">
            <h2>Скачайте наши материалы</h2>
            <div class="download-files">
                <a href="assets/files/Каталог.docx" download class="download-btn">
                    <img src="assets/media/info/download.svg" alt="">
                    Каталог цветов
                </a>
                <a href="assets/files/Прайс.docx" download class="download-btn">
                    <img src="assets/media/info/download.svg" alt="">
                    Прайс-лист
                </a>
                <a href="assets/files/Гид.docx" download class="download-btn">
                    <img src="assets/media/info/download.svg" alt="">
                    Гид по уходу
                </a>
            </div>
        </div>
    </div>
    <!-- files end -->
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.slide');
        let currentIndex = 0;
        const intervalTime = 3000;
        function switchSlides() {
            slides[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % slides.length;
            slides[currentIndex].classList.add('active');
        }
        let slideInterval = setInterval(switchSlides, intervalTime);
        const sliderContainer = document.querySelector('.about_right');
        sliderContainer.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        sliderContainer.addEventListener('mouseleave', () => {
            slideInterval = setInterval(switchSlides, intervalTime);
        });
    });
</script>