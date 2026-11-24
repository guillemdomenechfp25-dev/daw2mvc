<link rel="stylesheet" href="css/auth.css">

<?php $redirect = htmlspecialchars($_GET['redirect'] ?? $_POST['redirect'] ?? ''); ?>

<section class="d-flex justify-content-center auth-section-login">
    <div class="p-md-5 auth-card">
        <h2 class="text-center text-uppercase fw-bold mb-4">Iniciar Sesión</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="index.php?controller=usuario&action=procesarLogin" method="POST">
            <input type="hidden" name="redirect" value="<?= $redirect ?>">

            <div class="mb-3">
                <label class="form-label text-uppercase titulo-input">Email</label>
                <input type="email" name="email" class="form-control auth-input" required>
            </div>

            <div class="mb-4">
                <label class="form-label text-uppercase titulo-input">Contraseña</label>
                <input type="password" name="password" class="form-control auth-input" required>
            </div>

            <button type="submit" class="btn w-100 py-2 mb-3 auth-btn">Entrar</button>
        </form>

        <p class="text-center mb-0 noCuenta">
            ¿No tienes cuenta?
            <a href="index.php?controller=usuario&action=registro<?= $redirect ? '&redirect=' . urlencode($redirect) : '' ?>"
                class="linkRegistro-Inicio">Regístrate</a>
        </p>
    </div>
</section>