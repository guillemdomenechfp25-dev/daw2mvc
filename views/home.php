<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Daw2mvc/css/home.css">
    <title>Document</title>
</head>

<body>
    <section class="banner-container">
        <a href="#" class="banner-link">
            <img src="img/home/banner.png" alt="Foto Banner" class="banner-img">
        </a>

        <div class="banner-content">
            <h1>SABORES QUE TE LLEVAN MÁS LEJOS</h1>
            <p>Una experiencia gastronómica donde el alto rendimiento y el diseño de vanguardia se fusionan en cada
                plato.</p>
            <a href="index.php?controlador=menu" class="boton-s1">
                VER CARTA <span class="btn-arrow">&rarr;</span>
            </a>
        </div>
    </section>

    <section class="slider-seccion py-5 mt-5">
        <div class="text-center mb-4">
            <h2>MÁS QUE HAMBURGUESAS</h2>
            <p class="mb-5">En CUPRAEATS combinamos ingredientes premium, recetas exclusivas y un
                estilo
                único, inspirado en la pasión por la velocidad y el diséño.</p>
        </div>

        <div class="slider-wrapper">
            <div class="slider-track" id="sliderTrack">

                <div class="slider-card">
                    <img src="img/home/slider-elige-tu-menu.jpeg" alt="Paso 1" class="card-bg">
                    <div class="card-overlay">
                        <h3>01. ELIGE TU MENÚ</h3>
                        <p class="mb-4">Hamburguesas de autor, sides y postres diseñados con
                            identidad CUPRA.</p>
                        <a href="#" class="boton-s1">Ver Carta</a>
                    </div>
                </div>

                <div class="slider-card">
                    <img src="img/home/slider-personaliza.jpeg" alt="Paso 2" class="card-bg">
                    <div class="card-overlay">
                        <h3>02. PERSONALIZA</h3>
                        <p class="mb-4">Añade o quita ingredientes en tiempo real antes de enviar
                            tu pedido.</p>
                        <a href="#" class="boton-s1">Personalizar</a>
                    </div>
                </div>

                <div class="slider-card">
                    <img src="img/home/slider-recibe-o-recoge.jpeg" alt="Paso 3" class="card-bg">
                    <div class="card-overlay">
                        <h3>03. RECIBE O RECOGE</h3>
                        <p class="mb-4">En la puerta de tu casa en tiempo récord o listo para
                            recoger sin colas.</p>
                        <a href="#" class="boton-s1">Pedir Ahora</a>
                    </div>
                </div>

            </div>

            <button class="slider-btn prev-btn" id="prevBtn" aria-label="Anterior">
                <span class="btn-arrow">&larr;</span>
            </button>

            <button class="slider-btn next-btn" id="nextBtn" aria-label="Siguiente">
                <span class="btn-arrow">&rarr;</span>
            </button>
        </div>
    </section>
    <script>
        const track = document.getElementById('sliderTrack');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        const cards = Array.from(track.querySelectorAll('.slider-card'));

        let currentIndex = 0;
        let animating = false;

        // Calcula la posición central
        function getPosition() {
            const cardWidth = cards[0].getBoundingClientRect().width;
            const gap = parseFloat(getComputedStyle(track).gap);
            const wrapperWidth = track.parentElement.getBoundingClientRect().width;

            const stride = cardWidth + gap;
            const centerOffset = (wrapperWidth - cardWidth) / 2;

            return {
                stride,
                position: centerOffset - stride
            };
        }

        // Organiza las tarjetas: anterior, actual, siguiente
        function renderSlider() {
            const previousIndex = (currentIndex - 1 + cards.length) % cards.length;
            const nextIndex = (currentIndex + 1) % cards.length;

            const orderedCards = [
                cards[previousIndex],
                cards[currentIndex],
                cards[nextIndex]
            ];

            orderedCards.forEach(card => track.appendChild(card));

            const { position } = getPosition();

            track.style.transition = 'none';
            track.style.transform = `translateX(${position}px)`;

            // Restablecer la transición
            track.offsetHeight;
            track.style.transition = '';
        }

        // Mover el carrusel
        function moveSlider(direction) {
            if (animating) return;

            animating = true;

            const { stride, position } = getPosition();

            const movement = direction === 'next' ? -stride : stride;

            track.style.transform = `translateX(${position + movement}px)`;

            track.addEventListener('transitionend', function finish() {

                track.removeEventListener('transitionend', finish);

                if (direction === 'next') {
                    currentIndex = (currentIndex + 1) % cards.length;
                } else {
                    currentIndex = (currentIndex - 1 + cards.length) % cards.length;
                }

                renderSlider();

                animating = false;

            });
        }

        // Flechas
        nextBtn.addEventListener('click', () => {
            moveSlider('next');
        });

        prevBtn.addEventListener('click', () => {
            moveSlider('prev');
        });

        // Inicio: 03 izquierda, 01 centro, 02 derecha
        window.addEventListener('load', renderSlider);

        window.addEventListener('resize', () => {
            if (!animating) {
                renderSlider();
            }
        });

        cards.forEach(card => {
            const img = card.querySelector('.card-bg');

            if (img) {
                img.loading = 'eager';
                img.decoding = 'async';

                if (!img.complete) {
                    img.setAttribute('fetchpriority', 'high');
                }
            }
        });
    </script>

    <!-- SECTION 2 -->
    <section class="destacados-section">
        <div class="destacados-header">
            <p class="subtitulo">PLATOS DESTACADOS</p>
            <h2>LO MÁS ELEGIDO</h2>
        </div>
    </section>
</body>

</html>