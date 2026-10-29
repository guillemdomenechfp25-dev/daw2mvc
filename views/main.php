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
        <nav class="navbar">
            <div class="container">
                <a class="navbar-brand">
                    <img src="img/global/logo claro.svg" alt="Logo CUPRAEATS" height="40">
                </a>

                <div class="d-flex gap-3">
                    <a href="#" class="nav-link text-white">Inicio</a>
                    <a href="#" class="nav-link text-white">Carta</a>
                    <a href="#" class="nav-link text-white">Nosotros</a>
                    <a href="#" class="nav-link text-white">Contacto</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <?php include_once $view; ?>
    </main>

    <footer>

    </footer>
</body>

</html>