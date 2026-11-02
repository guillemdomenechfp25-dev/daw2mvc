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
        <nav class="navbar nav-class">
            <div class="container-fluid px-5">
                <div class="navbar-brand">
                    <a href="#">
                        <img src="img/global/logo claro.svg" alt="Logo CUPRAEATS" height="70">
                    </a>
                </div>

                <div class="d-flex align-items-center gap-4">

                    <div class="d-flex gap-4">
                        <a href="#" class="nav-link nav-item">Inicio</a>
                        <a href="#" class="nav-link nav-item">Carta</a>
                        <a href="#" class="nav-link nav-item">Nosotros</a>
                        <a href="#" class="nav-link nav-item">Contacto</a>
                    </div>

                    <div class="d-flex gap-3 ms-2">
                        <a href="#">
                            <img src=" img/global/icono carrito.svg" alt="Carrito" height="30">
                        </a>

                        <a href="#">
                            <img src="img/global/icono perfil.svg" alt="Perfil" height="30">
                        </a>
                    </div>

                </div>

            </div>
        </nav>
    </header>

    <main>
        <?php include_once $view; ?>
    </main>

    <footer class="py-4 footer-class">
        <div class="d-flex flex-column gap-3">
            <!-- 1. ARRIBA:-->
            <div class="d-flex justify-content-center gap-4 mt-3">
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

            <!-- 2. MEDIO:-->
            <div class="d-flex justify-content-center footer-links mt-3">
                <a href="#" class="nav-item text-decoration-none px-3">Menú</a>
                <span class="separador">|</span>
                <a href="#" class="nav-item text-decoration-none px-3">Contacto</a>
                <span class="separador">|</span>
                <a href="#" class="nav-item text-decoration-none px-3">Información Legal</a>
            </div>
            <!-- 3. ABAJO: Copyright centrado -->
            <div class="text-center mt-3 mb-3 footer-derechos">
                <p class="m-0">&copy; 2026 CUPRAEATS. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>

</html>