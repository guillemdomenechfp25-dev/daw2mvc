<link rel="stylesheet" href="css/auth.css">

<?php $redirect = htmlspecialchars($_GET['redirect'] ?? $_POST['redirect'] ?? ''); ?>

<section class="d-flex justify-content-center pb-5 auth-section-registro">
    <div class="p-md-5 auth-card">
        <h2 class="text-center text-uppercase fw-bold mb-4">Crear Cuenta</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="index.php?controller=usuario&action=procesarRegistro" method="POST">
            <input type="hidden" name="redirect" value="<?= $redirect ?>">

            <div class="mb-3">
                <label class="form-label text-uppercase titulo-input">Nombre</label>
                <input type="text" name="nombre" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-uppercase titulo-input">Email</label>
                <input type="email" name="email" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-uppercase titulo-input">Teléfono</label>
                <input type="tel" name="telefono" class="form-control auth-input" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-uppercase titulo-input">Dirección</label>
                <input type="text" name="direccion" class="form-control auth-input" required>
            </div>

            <div class="mb-4">
                <label class="form-label text-uppercase titulo-input">Contraseña</label>
                <input type="password" name="password" class="form-control auth-input" required>
            </div>

            <button type="submit" class="btn w-100 py-2 mb-3 auth-btn">Registrarse</button>
        </form>

        <p class="text-center mb-0 noCuenta">
            ¿Ya tienes cuenta?
            <a href="index.php?controller=usuario&action=login<?= $redirect ? '&redirect=' . urlencode($redirect) : '' ?>"
                class="linkRegistro-Inicio">Inicia sesión</a>
        </p>
    </div>
</section>