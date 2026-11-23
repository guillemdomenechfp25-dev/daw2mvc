<link rel="stylesheet" href="css/auth.css">

<?php $redirect = htmlspecialchars($_GET['redirect'] ?? $_POST['redirect'] ?? ''); ?>

<section class="container-fluid d-flex align-items-center justify-content-center py-5 auth-section">
    <div class="p-4 p-md-5 auth-card">
        <h2 class="text-center text-uppercase fw-bold mb-4">Crear Cuenta</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="index.php?controller=usuario&action=procesarRegistro" method="POST">
            <input type="hidden" name="redirect" value="<?= $redirect ?>">

            <div class="mb-3">
                <label class="form-label small text-uppercase">Nombre</label>
                <input type="text" name="nombre" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label small text-uppercase">Email</label>
                <input type="email" name="email" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label small text-uppercase">Teléfono</label>
                <input type="tel" name="telefono" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label small text-uppercase">Dirección</label>
                <input type="text" name="direccion" class="form-control auth-input" required>
            </div>

            <div class="mb-4">
                <label class="form-label small text-uppercase">Contraseña</label>
                <input type="password" name="password" class="form-control auth-input" required>
            </div>

            <button type="submit" class="btn w-100 py-2 mb-3 auth-btn">Registrarse</button>
        </form>

        <p class="text-center small text-secondary mb-0">
            ¿Ya tienes cuenta?
            <a href="index.php?controller=usuario&action=login<?= $redirect ? '&redirect=' . urlencode($redirect) : '' ?>"
                class="text-white">Inicia sesión</a>
        </p>
    </div>
</section>