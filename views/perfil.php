<link rel="stylesheet" href="css/perfil.css">

<section class="container-fluid d-flex align-items-center justify-content-center py-5 perfil-section">
    <div class="p-4 p-md-5 perfil-card">
        <h2 class="text-center text-uppercase fw-bold mb-4">Mi Perfil</h2>

        <div class="d-grid gap-3 mb-4">
            <div class="perfil-dato">
                <span class="perfil-label">Nombre</span>
                <span class="perfil-valor fw-semibold"><?= htmlspecialchars($usuario['nombre']) ?></span>
            </div>

            <div class="perfil-dato">
                <span class="perfil-label">Email</span>
                <span class="perfil-valor"><?= htmlspecialchars($usuario['email']) ?></span>
            </div>

            <div class="perfil-dato">
                <span class="perfil-label">Teléfono</span>
                <span class="perfil-valor"><?= htmlspecialchars($usuario['telefono']) ?></span>
            </div>

            <div class="perfil-dato">
                <span class="perfil-label">Dirección habitual</span>
                <span class="perfil-valor"><?= htmlspecialchars($usuario['direccion']) ?></span>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="index.php?controller=carta&action=index" class="btn py-2 perfil-btn-principal">
                Volver a la carta
            </a>
            <a href="index.php?controller=usuario&action=logout"
                class="btn btn-outline-danger py-2 fw-bold text-uppercase">
                Cerrar sesión
            </a>
        </div>
    </div>
</section>