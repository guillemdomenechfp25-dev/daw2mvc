<link rel="stylesheet" href="css/entrega.css">

<?php
$carrito = $_SESSION['carrito'] ?? [];
$usuario = $_SESSION['usuario'] ?? [];

$subtotal = 0;
foreach ($carrito as $prod) {
    $subtotal += $prod['precio'] * $prod['cantidad'];
}
$iva = $subtotal * 0.10;
$gastosEnvioInicial = 4.90;
$totalInicial = $subtotal + $gastosEnvioInicial;
?>

<section class="container-fluid header-container py-5 entrega-section">
    <!-- CABECERA: TÍTULO + LINEA TEMPORAL (Paso 2 activo) -->
    <div class="text-center mb-5">
        <h1 class="text-uppercase fw-bold mb-4 entrega-titulo">Entrega</h1>
        <div class="d-flex justify-content-center align-items-center gap-4 entrega-progreso-texto">
            <!-- Paso 1: Carrito (completado / clicable para volver) -->
            <a href="index.php?controller=carrito&action=index"
                class="d-flex align-items-center gap-2 text-decoration-none opacity-75">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center entrega-redonda-offline">1</span>
                <span class="entrega-texto-offline">Carrito</span>
            </a>
            <div class="entrega-linea"></div>

            <!-- Paso 2: Entrega (Activo) -->
            <div class="d-flex align-items-center gap-2">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center fw-bold entrega-redonda-online">2</span>
                <span class="entrega-texto-online">Entrega</span>
            </div>
            <div class="entrega-linea"></div>

            <!-- Paso 3: Pago (Inactivo) -->
            <div class="d-flex align-items-center gap-2 opacity-50">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center entrega-redonda-offline">3</span>
                <span class="entrega-texto-offline">Pago</span>
            </div>
            <div class="entrega-linea"></div>

            <!-- Paso 4: Confirmación (Inactivo) -->
            <div class="d-flex align-items-center gap-2 opacity-50">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center entrega-redonda-offline">4</span>
                <span class="entrega-texto-offline">Confirmación</span>
            </div>
        </div>
    </div>

    <form action="index.php?controller=carrito&action=guardarEntrega" method="POST">
        <div class="row g-4">
            <!-- COLUMNA IZQUIERDA -->
            <div class="col-lg-8">
                <!-- TARJETAS TIPO DE ENTREGA -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="entrega-tipo-card d-flex align-items-center gap-4 w-100">
                            <input type="radio" name="tipo_entrega" value="domicilio" class="d-none" checked>
                            <span class="entrega-radio-visual"></span>
                            <div>
                                <div class="entrega-icono-circulo">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.6">
                                        <path d="M1 3h15v13H1z" />
                                        <path d="M16 8h4l3 3v5h-7V8z" />
                                        <circle cx="5.5" cy="18.5" r="2.5" />
                                        <circle cx="18.5" cy="18.5" r="2.5" />
                                    </svg>
                                </div>
                                <div class="text-uppercase fw-bold small mb-1">A domicilio</div>
                                <div class="entrega-tipo-desc">Recibe tu pedido en la dirección indicada.</div>
                            </div>
                        </label>
                    </div>

                    <div class="col-md-6">
                        <label class="entrega-tipo-card d-flex align-items-center gap-4 w-100">
                            <input type="radio" name="tipo_entrega" value="recogida" class="d-none">
                            <span class="entrega-radio-visual"></span>
                            <div>
                                <div class="entrega-icono-circulo">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.6">
                                        <path d="M3 9l1.5-5h15L21 9" />
                                        <path d="M3 9a3 3 0 0 0 6 0 3 3 0 0 0 6 0 3 3 0 0 0 6 0" />
                                        <path d="M5 12v8h14v-8" />
                                        <path d="M10 20v-5h4v5" />
                                    </svg>
                                </div>
                                <div class="text-uppercase fw-bold small mb-1">Recogida en local</div>
                                <div class="entrega-tipo-desc">En el restaurante CUPRAEATS.</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- TARJETA DIRECCIÓN DE ENTREGA -->
                <div class="p-4 entrega-card" id="bloque-direccion">
                    <p class="text-uppercase fw-bold mb-4 entrega-subtitulo">DIRECCIÓN DE ENTREGA</p>

                    <div class="mb-3">
                        <label class="form-label entrega-label">Nombre y apellidos</label>
                        <input type="text" name="nombre_completo" class="form-control entrega-input"
                            placeholder="Ej. Juan Pérez" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label entrega-label">Dirección</label>
                        <input type="text" name="direccion_entrega" class="form-control entrega-input"
                            placeholder="Ej. Calle Mayor, 12"
                            value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label entrega-label">Código postal</label>
                            <input type="text" name="codigo_postal" class="form-control entrega-input"
                                placeholder="Ej. 28001" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label entrega-label">Ciudad</label>
                            <input type="text" name="ciudad_entrega" class="form-control entrega-input"
                                placeholder="Ej. Madrid" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label entrega-label">Teléfono</label>
                            <input type="tel" name="telefono" class="form-control entrega-input"
                                placeholder="Ej. 600 123 456"
                                value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <!-- BLOQUE RECOGIDA EN LOCAL (oculto por defecto) -->
                <div class="p-4 entrega-card d-none" id="bloque-recogida">
                    <p class="text-uppercase fw-bold mb-2 entrega-subtitulo">RECOGIDA EN RESTAURANTE</p>
                    <p class="entrega-tipo-desc mb-0">
                        Tu pedido estará listo para recoger en <strong class="text-white">CUPRA EATS Flagship</strong>
                        en unos 20-25 minutos.
                    </p>
                </div>

                <!-- BOTÓN INFERIOR IZQUIERDO -->
                <div class="mt-4">
                    <a href="index.php?controller=carrito&action=index" class="px-3 py-2 entrega-boton-volver">
                        ← VOLVER AL CARRITO
                    </a>
                </div>
            </div>

            <!-- COLUMNA DERECHA: RESUMEN DEL PEDIDO -->
            <div class="col-lg-4">
                <div class="p-4 entrega-card">
                    <p class="text-uppercase fw-bold mb-4 entrega-resumen-titulo">RESUMEN DEL PEDIDO</p>

                    <div class="d-flex justify-content-between mb-2 opacity-75 small">
                        <span class="entrega-subtotal">Subtotal</span>
                        <span class="entrega-subtotal"><?= number_format($subtotal, 2, ',', '.') ?> €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 entrega-subtotal">
                        <span>Descuento</span>
                        <span>-</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2 entrega-subtotal">
                        <span>Gastos de envío</span>
                        <span id="texto-envio"><?= number_format($gastosEnvioInicial, 2, ',', '.') ?> €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 entrega-subtotal">
                        <span>IVA (10%)</span>
                        <span><?= number_format($iva, 2, ',', '.') ?> €</span>
                    </div>

                    <hr class="my-3 entrega-total-linea">

                    <div class="d-flex justify-content-between fw-bold mb-4">
                        <span class="entrega-total">TOTAL</span>
                        <span class="entrega-total" id="texto-total"><?= number_format($totalInicial, 2, ',', '.') ?>
                            €</span>
                    </div>

                    <button type="submit" class="entrega-boton-continuar">
                        CONTINUAR AL PAGO →
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

<script>
    const subtotal = <?= json_encode((float) $subtotal) ?>;
    const radiosEntrega = document.querySelectorAll('input[name="tipo_entrega"]');
    const bloqueDireccion = document.getElementById('bloque-direccion');
    const bloqueRecogida = document.getElementById('bloque-recogida');
    const inputsDireccion = bloqueDireccion.querySelectorAll('input');
    const textoEnvio = document.getElementById('texto-envio');
    const textoTotal = document.getElementById('texto-total');

    function formatearEuros(valor) {
        return valor.toFixed(2).replace('.', ',') + ' €';
    }

    radiosEntrega.forEach(radio => {
        radio.addEventListener('change', () => {
            const esDomicilio = radio.value === 'domicilio';
            bloqueDireccion.classList.toggle('d-none', !esDomicilio);
            bloqueRecogida.classList.toggle('d-none', esDomicilio);

            inputsDireccion.forEach(inp => inp.required = esDomicilio);

            const envio = esDomicilio ? 4.90 : 0.00;
            textoEnvio.textContent = formatearEuros(envio);
            textoTotal.textContent = formatearEuros(subtotal + envio);
        });
    });
</script>