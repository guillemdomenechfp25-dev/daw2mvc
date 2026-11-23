<link rel="stylesheet" href="css/pago.css">

<?php
$carrito = $_SESSION['carrito'] ?? [];
$entrega = $_SESSION['entrega'] ?? [];

$subtotal = 0;
foreach ($carrito as $prod) {
    $subtotal += $prod['precio'] * $prod['cantidad'];
}
$iva = $subtotal * 0.10;
$gastosEnvio = (float) ($entrega['gastos_envio'] ?? 0);
$total = $subtotal + $gastosEnvio;
?>

<section class="container-fluid header-container py-5 pago-section">
    <!-- CABECERA: TÍTULO + LINEA TEMPORAL (Paso 3 activo) -->
    <div class="text-center mb-5">
        <h1 class="text-uppercase fw-bold mb-4 pago-titulo">Pago</h1>
        <div class="d-flex justify-content-center align-items-center gap-4 pago-progreso-texto">
            <!-- Paso 1: Carrito -->
            <a href="index.php?controller=carrito&action=index"
                class="d-flex align-items-center gap-2 text-decoration-none opacity-75">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center pago-redonda-offline">1</span>
                <span class="pago-texto-offline">Carrito</span>
            </a>
            <div class="pago-linea"></div>

            <!-- Paso 2: Entrega -->
            <a href="index.php?controller=carrito&action=entrega"
                class="d-flex align-items-center gap-2 text-decoration-none opacity-75">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center pago-redonda-offline">2</span>
                <span class="pago-texto-offline">Entrega</span>
            </a>
            <div class="entrega-linea pago-linea"></div>

            <!-- Paso 3: Pago (Activo) -->
            <div class="d-flex align-items-center gap-2">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center fw-bold pago-redonda-online">3</span>
                <span class="pago-texto-online">Pago</span>
            </div>
            <div class="pago-linea"></div>

            <!-- Paso 4: Confirmación (Inactivo) -->
            <div class="d-flex align-items-center gap-2 opacity-50">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center pago-redonda-offline">4</span>
                <span class="pago-texto-offline">Confirmación</span>
            </div>
        </div>
    </div>

    <form action="index.php?controller=carrito&action=confirmarPedido" method="POST">
        <div class="row g-4">
            <!-- COLUMNA IZQUIERDA -->
            <div class="col-lg-8">
                <!-- RESUMEN DATOS ENTREGA ELEGIDOS -->
                <div class="p-4 pago-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="text-uppercase fw-bold m-0 pago-subtitulo">DATOS DE ENTREGA</p>
                        <a href="index.php?controller=carrito&action=entrega" class="small text-decoration-none"
                            style="color: rgb(196, 150, 120);">
                            Modificar
                        </a>
                    </div>
                    <p class="small mb-1 text-white">
                        <strong><?= $entrega['tipo_entrega'] === 'domicilio' ? 'Envío a domicilio' : 'Recogida en local' ?></strong>
                        — <?= htmlspecialchars($entrega['nombre_completo']) ?>
                        (<?= htmlspecialchars($entrega['telefono']) ?>)
                    </p>
                    <?php if ($entrega['tipo_entrega'] === 'domicilio'): ?>
                        <p class="small mb-0" style="color: rgb(200, 200, 200);">
                            <?= htmlspecialchars($entrega['direccion_entrega']) ?>,
                            <?= htmlspecialchars($entrega['codigo_postal']) ?>
                            <?= htmlspecialchars($entrega['ciudad_entrega']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- SELECCIÓN MÉTODO DE PAGO -->
                <div class="p-4 pago-card">
                    <p class="text-uppercase fw-bold mb-4 pago-subtitulo">MÉTODO DE PAGO</p>

                    <div class="d-grid gap-3 mb-4">
                        <label class="pago-opcion-card d-flex align-items-center gap-3">
                            <input type="radio" name="metodo_pago" value="tarjeta" class="d-none" checked>
                            <span class="pago-radio-visual"></span>
                            <div>
                                <div class="text-uppercase fw-bold small">Tarjeta de crédito / débito</div>
                                <div class="small" style="color: rgb(200, 200, 200);">Pago inmediato seguro (simulado)
                                </div>
                            </div>
                        </label>

                        <label class="pago-opcion-card d-flex align-items-center gap-3">
                            <input type="radio" name="metodo_pago" value="efectivo" class="d-none">
                            <span class="pago-radio-visual"></span>
                            <div>
                                <div class="text-uppercase fw-bold small">Efectivo</div>
                                <div class="small" style="color: rgb(200, 200, 200);">Paga en el momento de la entrega o
                                    recogida</div>
                            </div>
                        </label>
                    </div>

                    <!-- FORMULARIO TARJETA SIMULADO -->
                    <div id="bloque-tarjeta">
                        <div class="mb-3">
                            <label class="form-label pago-label">Titular de la tarjeta</label>
                            <input type="text" name="titular_tarjeta" class="form-control pago-input"
                                placeholder="Ej. Juan Pérez"
                                value="<?= htmlspecialchars($entrega['nombre_completo'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label pago-label">Número de tarjeta</label>
                            <input type="text" name="numero_tarjeta" class="form-control pago-input"
                                placeholder="1234 5678 9012 3456" maxlength="19" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <label class="form-label pago-label">Caducidad</label>
                                <input type="text" name="caducidad_tarjeta" class="form-control pago-input"
                                    placeholder="MM/AA" maxlength="5" required>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label pago-label">CVV</label>
                                <input type="text" name="cvv_tarjeta" class="form-control pago-input" placeholder="123"
                                    maxlength="3" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTÓN VOLVER A ENTREGA -->
                <div class="mt-4">
                    <a href="index.php?controller=carrito&action=entrega" class="px-3 py-2 pago-boton-volver">
                        ← VOLVER A ENTREGA
                    </a>
                </div>
            </div>

            <!-- COLUMNA DERECHA: RESUMEN FINAL -->
            <div class="col-lg-4">
                <div class="p-4 pago-card">
                    <p class="text-uppercase fw-bold mb-4 pago-resumen-titulo">RESUMEN DEL PEDIDO</p>

                    <div class="d-flex justify-content-between mb-2 opacity-75 small">
                        <span class="pago-subtotal">Subtotal</span>
                        <span class="pago-subtotal"><?= number_format($subtotal, 2, ',', '.') ?> €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 pago-subtotal">
                        <span>Descuento</span>
                        <span>-</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 pago-subtotal">
                        <span>Gastos de envío</span>
                        <span><?= number_format($gastosEnvio, 2, ',', '.') ?> €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 pago-subtotal">
                        <span>IVA (10%)</span>
                        <span><?= number_format($iva, 2, ',', '.') ?> €</span>
                    </div>

                    <hr class="my-3 pago-total-linea">

                    <div class="d-flex justify-content-between fw-bold mb-4">
                        <span class="pago-total">TOTAL A PAGAR</span>
                        <span class="pago-total"><?= number_format($total, 2, ',', '.') ?> €</span>
                    </div>

                    <button type="submit" class="pago-boton-confirmar">
                        PAGAR Y FINALIZAR PEDIDO
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    const radiosPago = document.querySelectorAll('input[name="metodo_pago"]');
    const bloqueTarjeta = document.getElementById('bloque-tarjeta');
    const inputsTarjeta = bloqueTarjeta.querySelectorAll('input');

    radiosPago.forEach(radio => {
        radio.addEventListener('change', () => {
            const esTarjeta = radio.value === 'tarjeta';
            bloqueTarjeta.classList.toggle('d-none', !esTarjeta);
            inputsTarjeta.forEach(inp => inp.required = esTarjeta);
        });
    });
</script>