<link rel="stylesheet" href="css/confirmacion.css">

<?php
$codigoPedido = '#CUP-' . str_pad((int) $pedido['id'], 4, '0', STR_PAD_LEFT);
$fechaHora = date('d/m/Y - H:i');
$textoEntrega = ($pedido['entrega']['tipo_entrega'] ?? 'domicilio') === 'domicilio'
    ? 'A domicilio'
    : 'Recogida en local';
?>

<section class="container-fluid header-container confirmacion-section">
    <!-- CABECERA: TÍTULO + LINEA TEMPORAL (Paso 4 activo) -->
    <div class="text-center mb-4">
        <h1 class="text-uppercase fw-bold mb-4 confirmacion-titulo">Confirmación</h1>
        <div class="d-flex justify-content-center align-items-center gap-4 confirmacion-progreso-texto">
            <!-- Paso 1 -->
            <div class="d-flex align-items-center gap-2 opacity-75">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center confirmacion-redonda-offline">1</span>
                <span class="confirmacion-texto-offline">Carrito</span>
            </div>
            <div class="confirmacion-linea"></div>

            <!-- Paso 2 -->
            <div class="d-flex align-items-center gap-2 opacity-75">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center confirmacion-redonda-offline">2</span>
                <span class="confirmacion-texto-offline">Entrega</span>
            </div>
            <div class="confirmacion-linea"></div>

            <!-- Paso 3 -->
            <div class="d-flex align-items-center gap-2 opacity-75">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center confirmacion-redonda-offline">3</span>
                <span class="confirmacion-texto-offline">Pago</span>
            </div>
            <div class="confirmacion-linea"></div>

            <!-- Paso 4: Confirmación (Activo) -->
            <div class="d-flex align-items-center gap-2">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center fw-bold confirmacion-redonda-online">4</span>
                <span class="confirmacion-texto-online">Confirmación</span>
            </div>
        </div>
    </div>

    <!-- BLOQUE CENTRAL: CHECK + MENSAJE + BOTÓN -->
    <div class="text-center confirmacion-hero">
        <div class="confirmacion-check-circulo">
            <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h2 class="text-uppercase fw-bold mb-3 confirmacion-estado-titulo">¡Pedido confirmado!</h2>

        <p class="confirmacion-subtexto mb-4">
            Tu pedido ha sido recibido correctamente.<br>
            En breve recibirás un email con todos los detalles.
        </p>

        <div class="d-flex flex-column align-items-center gap-3">
            <a href="index.php?controller=carta&action=index" class="confirmacion-boton-carta">
                Volver a la carta &rarr;
            </a>
            <a href="index.php?controller=usuario&action=perfil" class="confirmacion-link-pedidos">
                Ver el historial de mis pedidos
            </a>
        </div>
    </div>

    <!-- 3 TARJETAS INFERIORES (NÚMERO, FECHA Y HORA, MÉTODO DE ENTREGA) -->
    <div class="mx-auto confirmacion-info-grid">
        <div class="row g-3">
            <!-- Tarjeta 1: Número de pedido -->
            <div class="col-md-4">
                <div class="confirmacion-info-card d-flex align-items-center gap-3">
                    <div class="confirmacion-info-icono">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="confirmacion-info-label">Número de pedido</div>
                        <div class="confirmacion-info-valor"><?= htmlspecialchars($codigoPedido) ?></div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Fecha y hora -->
            <div class="col-md-4">
                <div class="confirmacion-info-card d-flex align-items-center gap-3">
                    <div class="confirmacion-info-icono">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div>
                        <div class="confirmacion-info-label">Fecha y hora</div>
                        <div class="confirmacion-info-valor"><?= htmlspecialchars($fechaHora) ?></div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Método de entrega -->
            <div class="col-md-4">
                <div class="confirmacion-info-card d-flex align-items-center gap-3">
                    <div class="confirmacion-info-icono">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.6">
                            <path d="M1 3h15v13H1z"></path>
                            <path d="M16 8h4l3 3v5h-7V8z"></path>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </div>
                    <div>
                        <div class="confirmacion-info-label">Método de entrega</div>
                        <div class="confirmacion-info-valor"><?= htmlspecialchars($textoEntrega) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>