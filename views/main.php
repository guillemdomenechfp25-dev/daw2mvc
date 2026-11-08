<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daw2mvc — Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <header>
        <nav class="navbar py-2">
            <div class="container-fluid header-container">
                <div class="row w-100 align-items-center m-0">
                    <div class="col-auto p-0">
                        <a class="navbar-brand m-0 p-0" href="index.php">
                            <img src="img/global/logo claro.svg" alt="Logo CUPRAEATS" height="70">
                        </a>
                    </div>

                    <div class="col d-flex justify-content-end align-items-center gap-4 p-0">
                        <div class="d-flex gap-4">
                            <a href="index.php?controller=producto" class="nav-link nav-item p-0">Inicio</a>
                            <a href="index.php?controller=producto&action=carta" class="nav-link nav-item p-0">Carta</a>
                            <a href="#" class="nav-link nav-item p-0">Nosotros</a>
                            <a href="#" class="nav-link nav-item p-0">Contacto</a>
                        </div>

                        <div class="d-flex align-items-center gap-3 ms-2">
                            <a href="#" class="icon-link">
                                <img src="img/global/icono carrito.svg" alt="Carrito" height="30">
                            </a>
                            <a href="#" class="icon-link">
                                <img src="img/global/icono perfil.svg" alt="Perfil" height="30">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <?php include_once $view; ?>
    </main>

    <footer class="py-4 footer-class">
        <div class="container-fluid header-container">
            <div class="row justify-content-center mt-3">
                <div class="col-auto d-flex justify-content-center align-items-center gap-4">
                    <a href="#" class="icon-link">
                        <img src="img/global/icono ig.svg" alt="Instagram" height="22">
                    </a>
                    <a href="#" class="icon-link">
                        <img src="img/global/icono yt.svg" alt="YouTube" height="17">
                    </a>
                    <a href="#" class="icon-link">
                        <img src="img/global/icono wsp.svg" alt="WhatsApp" height="22">
                    </a>
                    <a href="#" class="icon-link">
                        <img src="img/global/icono correo.svg" alt="Correo" height="20">
                    </a>
                </div>
            </div>

            <div class="row justify-content-center footer-links mt-3">
                <div class="col-auto d-flex align-items-center">
                    <a href="index.php?controller=producto&action=carta"
                        class="nav-item text-decoration-none px-3">Menú</a>
                    <span class="separador">|</span>
                    <a href="#" class="nav-item text-decoration-none px-3">Contacto</a>
                    <span class="separador">|</span>
                    <a href="#" class="nav-item text-decoration-none px-3">Información Legal</a>
                </div>
            </div>

            <div class="row justify-content-center mt-3 mb-3 footer-derechos">
                <div class="col-12 text-center">
                    <p class="m-0">&copy; 2026 CUPRAEATS. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        let lastScrollTop = 0;
        const header = document.querySelector('header');

        window.addEventListener('scroll', () => {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScroll > lastScrollTop && currentScroll > 75) {
                header.classList.add('header-hidden');
            } else {
                header.classList.remove('header-hidden');
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });
    </script>
</body>

</html>