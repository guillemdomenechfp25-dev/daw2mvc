<!DOCTYPE html>
<html lang="es">

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
                VER CARTA
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

            track.offsetHeight;
            track.style.transition = '';
        }

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

        nextBtn.addEventListener('click', () => {
            moveSlider('next');
        });

        prevBtn.addEventListener('click', () => {
            moveSlider('prev');
        });

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

    <section class="destacados-section mb-5">
        <div class="destacados-header">
            <p class="subtitulo">PLATOS DESTACADOS</p>
            <h2>LO MÁS ELEGIDO</h2>
        </div>

        <div class="destacados-grid">
            <article class="product-card">
                <div class="card-img-wrapper">
                    <img src="img/home/banner.png" alt="CUPRA Classic" class="product-img">
                </div>
                <div class="card-glass-content">
                    <div class="product-info">
                        <h3 class="product-name">CUPRA CLASSIC</h3>
                        <span class="product-price">12,90 €</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn-add">AÑADIR AL CARRITO</button>
                        <button class="btn-edit" title="Personalizar / Editar">&#9881;</button>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="card-img-wrapper">
                    <img src="img/home/banner.png" alt="Nitro BBQ" class="product-img">
                </div>
                <div class="card-glass-content">
                    <div class="product-info">
                        <h3 class="product-name">NITRO BBQ</h3>
                        <span class="product-price">14,90 €</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn-add">AÑADIR AL CARRITO</button>
                        <button class="btn-edit" title="Personalizar / Editar">&#9881;</button>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="card-img-wrapper">
                    <img src="img/home/banner.png" alt="Loaded Fries" class="product-img">
                </div>
                <div class="card-glass-content">
                    <div class="product-info">
                        <h3 class="product-name">LOADED FRIES</h3>
                        <span class="product-price">8,90 €</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn-add">AÑADIR AL CARRITO</button>
                        <button class="btn-edit" title="Personalizar / Editar">&#9881;</button>
                    </div>
                </div>
            </article>

            <article class="product-card">
                <div class="card-img-wrapper">
                    <img src="img/home/banner.png" alt="Combo CUPRA" class="product-img">
                </div>
                <div class="card-glass-content">
                    <div class="product-info">
                        <h3 class="product-name">COMBO CUPRA</h3>
                        <span class="product-price">16,90 €</span>
                    </div>
                    <div class="card-actions">
                        <button class="btn-add">AÑADIR AL CARRITO</button>
                        <button class="btn-edit" title="Personalizar / Editar">&#9881;</button>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <section class="experiencia-section experiencia-glow py-5">
        <div class="container-fluid p-0">
            <div class="row m-0 align-items-center g-0 experiencia-card">
                <div class="col-12 col-lg-7 p-0">
                    <div class="experiencia-img-wrapper">
                        <img src="img/home/local.jpeg" alt="Local CUPRAEATS" class="img-fluid w-100 experiencia-img">
                    </div>
                </div>
                <div class="col-12 col-lg-5 p-4 p-xl-5 d-flex flex-column justify-content-center experiencia-contenido">
                    <h3 class="subtitulo-corta text-uppercase mb-2">EXPERIENCIA</h3>
                    <h2 class="titulo-experiencia fw-bold mb-3">DISEÑADO PARA<br>LOS QUE SIENTEN</h2>
                    <p class="texto-experiencia mb-4">
                        Disfruta de una experiencia gastronómica única en un entorno que combina la innovación, el
                        diseño y el auténtico sabor CUPRA.
                    </p>
                    <div class="row m-0 mb-4 g-3">
                        <div class="col-12 col-sm-6 p-0 d-flex align-items-center gap-3">
                            <img src="img/global/icono ubicacion.svg" alt="Ubicación" height="35"
                                class="experiencia-icono">
                            <div>
                                <p class="experiencia-info-titulo fw-bold small m-0">Ubicación</p>
                                <p class="experiencia-info-desc small m-0">Centro de ciudad</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 p-0 d-flex align-items-center gap-3">
                            <img src="img/global/icono horario-calendario.svg" alt="Horario" height="35"
                                class="experiencia-icono">
                            <div>
                                <p class="experiencia-info-titulo fw-bold small m-0">Horario</p>
                                <p class="experiencia-info-desc small m-0">Lun - Dom 12:00 - 00:00</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="index.php?controller=producto&action=carta" class="btn-experiencia">
                            HAZ TU PEDIDO AHORA
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</body>

</html>