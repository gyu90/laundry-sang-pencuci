document.addEventListener('DOMContentLoaded', function () {

    const carousel = document.querySelector('.home-service-carousel');
    const track = document.querySelector('.home-service-track');

    if (!carousel || !track) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil seluruh card layanan
    |--------------------------------------------------------------------------
    */

    const allCards = Array.from(
        track.querySelectorAll('.home-service-card')
    );

    if (allCards.length === 0) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Pastikan tombol tersedia
    |--------------------------------------------------------------------------
    */

    let prevButton = carousel.querySelector('.home-service-arrow-prev');
    let nextButton = carousel.querySelector('.home-service-arrow-next');

    if (!prevButton) {
        prevButton = document.createElement('button');

        prevButton.type = 'button';
        prevButton.className =
            'home-service-arrow home-service-arrow-prev';

        prevButton.setAttribute(
            'aria-label',
            'Layanan sebelumnya'
        );

        prevButton.textContent = '‹';

        carousel.appendChild(prevButton);
    }

    if (!nextButton) {
        nextButton = document.createElement('button');

        nextButton.type = 'button';
        nextButton.className =
            'home-service-arrow home-service-arrow-next';

        nextButton.setAttribute(
            'aria-label',
            'Layanan berikutnya'
        );

        nextButton.textContent = '›';

        carousel.appendChild(nextButton);
    }


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    let pagination = carousel.querySelector(
        '.home-service-pagination'
    );

    if (!pagination) {
        pagination = document.createElement('div');

        pagination.className =
            'home-service-pagination';

        carousel.appendChild(pagination);
    }


    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    let currentSlide = 0;
    let isMobile = window.matchMedia(
        '(max-width: 768px)'
    ).matches;


    /*
    |--------------------------------------------------------------------------
    | Berapa layanan dalam 1 slide?
    |--------------------------------------------------------------------------
    */

    function getItemsPerSlide() {
        return isMobile ? 1 : 4;
    }


    /*
    |--------------------------------------------------------------------------
    | Buat ulang slide
    |--------------------------------------------------------------------------
    */

    function buildSlides(resetPosition = true) {

        const itemsPerSlide = getItemsPerSlide();

        track.innerHTML = '';

        for (
            let i = 0;
            i < allCards.length;
            i += itemsPerSlide
        ) {

            const slide = document.createElement('div');

            slide.className =
                'home-service-slide';

            const cards = allCards.slice(
                i,
                i + itemsPerSlide
            );

            cards.forEach(function (card) {
                slide.appendChild(card);
            });

            track.appendChild(slide);
        }


        if (resetPosition) {
            currentSlide = 0;
        }


        updateCarousel();
    }


    /*
    |--------------------------------------------------------------------------
    | Ambil slide yang sedang aktif
    |--------------------------------------------------------------------------
    */

    function getSlides() {

        return Array.from(
            track.querySelectorAll('.home-service-slide')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Buat pagination
    |--------------------------------------------------------------------------
    */

    function buildPagination() {

        const slides = getSlides();

        pagination.innerHTML = '';

        if (slides.length <= 1) {

            pagination.style.display = 'none';

            return;
        }

        pagination.style.display = 'flex';


        slides.forEach(function (_, index) {

            const dot = document.createElement('button');

            dot.type = 'button';

            dot.className =
                'home-service-dot';

            dot.setAttribute(
                'aria-label',
                `Lihat layanan ${index + 1}`
            );

            dot.addEventListener(
                'click',
                function () {

                    currentSlide = index;

                    updateCarousel();
                }
            );

            pagination.appendChild(dot);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Update carousel
    |--------------------------------------------------------------------------
    */

    function updateCarousel() {

        const slides = getSlides();

        if (slides.length === 0) {
            return;
        }


        if (currentSlide < 0) {
            currentSlide = 0;
        }

        if (currentSlide > slides.length - 1) {
            currentSlide = slides.length - 1;
        }


        track.style.transform =
            `translateX(-${currentSlide * 100}%)`;


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        const dots = pagination.querySelectorAll(
            '.home-service-dot'
        );

        dots.forEach(function (dot, index) {

            dot.classList.toggle(
                'active',
                index === currentSlide
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Tombol
        |--------------------------------------------------------------------------
        */

        prevButton.disabled =
            currentSlide === 0;

        nextButton.disabled =
            currentSlide === slides.length - 1;


        /*
        |--------------------------------------------------------------------------
        | Pagination dibuat ulang apabila jumlah slide berubah
        |--------------------------------------------------------------------------
        */

        if (dots.length !== slides.length) {
            buildPagination();

            const newDots =
                pagination.querySelectorAll(
                    '.home-service-dot'
                );

            newDots.forEach(function (dot, index) {

                dot.classList.toggle(
                    'active',
                    index === currentSlide
                );
            });
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Next
    |--------------------------------------------------------------------------
    */

    nextButton.addEventListener(
        'click',
        function () {

            const slides = getSlides();

            if (
                currentSlide <
                slides.length - 1
            ) {

                currentSlide++;

                updateCarousel();
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Previous
    |--------------------------------------------------------------------------
    */

    prevButton.addEventListener(
        'click',
        function () {

            if (currentSlide > 0) {

                currentSlide--;

                updateCarousel();
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Responsive mode
    |--------------------------------------------------------------------------
    */

    function checkResponsiveMode() {

        const newIsMobile =
            window.matchMedia(
                '(max-width: 768px)'
            ).matches;

        if (newIsMobile !== isMobile) {

            isMobile = newIsMobile;

            buildSlides(true);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Inisialisasi
    |--------------------------------------------------------------------------
    */

    buildSlides(true);

    window.addEventListener(
        'resize',
        checkResponsiveMode
    );

});