<link rel="stylesheet" href="css/carrito.css">

<section class="container-fluid header-container py-5 carrito-section">
    <div class="text-center mb-5">
        <h1 class="text-uppercase fw-bold mb-4 carrito-titulo">Carrito</h1>
        <div class="d-flex justify-content-center align-items-center gap-4 carrito-progreso-texto">
            <!-- Paso 1: Activo -->
            <div class="d-flex align-items-center gap-2">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center fw-bold carrito-redonda-online">1</span>
                <span class="carrito-texto-online">Carrito</span>
            </div>
            <div class="carrito-linea"></div>

            <!-- Paso 2: Inactivo -->
            <div class="d-flex align-items-center gap-2 opacity-50">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center carrito-redonda-offline">2</span>
                <span class="carrito-texto-offline">Entrega</span>
            </div>
            <div class="carrito-linea"></div>

            <!-- Paso 3: Inactivo -->
            <div class="d-flex align-items-center gap-2 opacity-50">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center carrito-redonda-offline">3</span>
                <span class="carrito-texto-offline">Pago</span>
            </div>
            <div class="carrito-linea"></div>

            <!-- Paso 4: Inactivo -->
            <div class="d-flex align-items-center gap-2 opacity-50">
                <span
                    class="rounded-circle d-inline-flex justify-content-center align-items-center carrito-redonda-offline">4</span>
                <span class="carrito-texto-offline">Confirmación</span>
            </div>
        </div>
    </div>

    <?php
    // Variables para el calculo del resumen
    $subtotal = 0;
    if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $prod) {
            $subtotal += $prod['precio'] * $prod['cantidad'];
        }
    }
    $iva = $subtotal * 0.10;
    $total = $subtotal;
    ?>

    <?php if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])): ?>
        <div class="row g-4">
            <!-- COLUMNA IZQUIERDA: LISTA DE PRODUCTOS -->
            <div class="col-lg-8">
                <div class="p-4 carrito-resumen-pedidos">
                    <!-- CABECERA DE LA TABLA/LISTA -->
                    <div class="row pb-3 mb-3 text-uppercase tuCarrito-header">
                        <div class="col-5">PRODUCTO</div>
                        <div class="col-2 text-center">PRECIO UNIDAD</div>
                        <div class="col-2 text-center">CANTIDAD</div>
                        <div class="col-2 text-center">TOTAL</div>
                        <div class="col-1"></div>
                    </div>

                    <!-- ELEMENTOS DEL CARRITO -->
                    <?php foreach ($_SESSION['carrito'] as $id => $item): ?>
                        <?php $totalProducto = $item['precio'] * $item['cantidad']; ?>

                        <div class="row align-items-center py-3 carrito-resumen-linea">
                            <!-- Imagen y Detalle -->
                            <div class="col-5 d-flex align-items-center gap-3">
                                <img src="<?= htmlspecialchars($item['imagen']) ?>"
                                    alt="<?= htmlspecialchars($item['nombre']) ?>" class="carrito-resumen-fotos">
                                <div>
                                    <h6 class="fw-bold text-uppercase carrito-resumen-nombreH">
                                        <?= htmlspecialchars($item['nombre']) ?>
                                    </h6>
                                    <small class="d-block carrito-resumen-lista-ingredientes">
                                        <?= htmlspecialchars(!empty($item['ingredientes']) ? $item['ingredientes'] : ($item['descripcion'] ?? '')) ?>
                                    </small>
                                </div>
                            </div>

                            <!-- Precio Unitario -->
                            <div class="col-2 text-center carrito-resumen-precio">
                                <?= number_format($item['precio'], 2, ',', '.') ?> €
                            </div>

                            <!-- Selector de Cantidad (- 1 +) -->
                            <div class="col-2 d-flex justify-content-center">
                                <div class="d-flex align-items-center border rounded px-2 py-1 gap-2 carrito-resumen-cantidad">
                                    <span class="carrito-simbolo-menos">-</span>
                                    <span>
                                        <?= $item['cantidad'] ?>
                                    </span>
                                    <span class="carrito-simbolo-mas">+</span>
                                </div>
                            </div>

                            <!-- Total por producto y Papelera -->
                            <div class="col-2 d-flex justify-content-center carrito-resumen-total">
                                <span class="fw-bold">
                                    <?= number_format($totalProducto, 2, ',', '.') ?> €
                                </span>
                            </div>

                            <!-- Columna exclusiva para la Papelera -->
                            <div class="col-1">
                                <a href="index.php?controller=producto&action=editar&id=<?= $id ?>" class="btn-edit"
                                    title="Personalizar / Editar">&#9881;</a>
                                <a href="index.php?controller=carrito&action=eliminar&id=<?= $id ?>">
                                    <img src="img/carrito/icono basura.svg" alt="Eliminar producto"
                                        class="carrito-icono-basura">
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <!-- BOTÓN INFERIOR IZQUIERDO -->
                <div class="mt-4">
                    <a href="index.php?controller=carta&action=index" class="px-3 py-2 carrito-boton-volver-carta">
                        ← VOLVER A LA CARTA
                    </a>
                </div>
            </div>

            <!-- COLUMNA DERECHA: RESUMEN DEL PEDIDO -->
            <div class="col-lg-4">
                <div class="p-4 carrito-resumen-pedido-dinero">
                    <p class="text-uppercase fw-bold mb-4 carrito-resumen-dinero-titulo">RESUMEN DEL PEDIDO</p>
                    <div class="d-flex justify-content-between mb-2 opacity-75 small">
                        <span class="carrito-subtotal">Subtotal</span>
                        <span class="carrito-subtotal">
                            <?= number_format($subtotal, 2, ',', '.') ?> €
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 carrito-subtotal">
                        <span>Descuento</span>
                        <span>-</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 carrito-subtotal">
                        <span>IVA (10%)</span>
                        <span>
                            <?= number_format($iva, 2, ',', '.') ?> €
                        </span>
                    </div>
                    <hr class="my-3 carrito-total-linea">
                    <div class="d-flex justify-content-between fw-bold mb-4">
                        <span class="carrito-total">TOTAL</span>
                        <span class="carrito-total">
                            <?= number_format($total, 2, ',', '.') ?> €
                        </span>
                    </div>
                    <a href="index.php?controller=carrito&action=entrega" class="carrito-boton-finalizar">
                        FINALIZAR COMPRA
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Si el carrito esta vacio o se vacia -->
        <div class="text-center py-5">
            <p class="carrito-vacio-texto">El carrito está vacío.</p>
            <a href="index.php?controller=carta&action=index" class="btn btn-outline-light">Volver a la carta</a>
        </div>
    <?php endif; ?>

</section>