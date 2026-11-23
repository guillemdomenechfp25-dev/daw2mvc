<link rel="stylesheet" href="css/personalizar.css">

<?php
$itemEditando = (!empty($claveEditando) && isset($_SESSION['carrito'][$claveEditando])) ? $_SESSION['carrito'][$claveEditando] : null;
$cantidadesGuardadas = $itemEditando['cantidades'] ?? [];
$cantidadProductoInicial = $itemEditando['cantidad'] ?? 1;
$imgProducto = !empty($producto->getImagen()) ? htmlspecialchars($producto->getImagen()) : 'img/home/banner.png';
$precioBase = (float) $producto->getPrecio();
$categoriaId = (int) $producto->getCategoriaId();
?>

<section class="personalizar-page">
    <div class="container py-4">

        <!-- Enlace volver -->
        <div class="mb-3">
            <a href="index.php?controller=carta&action=index" class="btn-volver">
                &larr; VOLVER A LA CARTA
            </a>
        </div>

        <form action="index.php?controller=carrito&action=guardarPersonalizacion" method="POST" id="form-personalizar">
            <input type="hidden" name="producto_id" value="<?= $producto->getId() ?>">
            <input type="hidden" name="clave_editando" value="<?= htmlspecialchars($claveEditando ?? '') ?>">

            <div class="row g-5 align-items-start">

                <!-- COLUMNA IZQUIERDA: IMAGEN + INGREDIENTES -->
                <div class="col-lg-6">
                    <div class="producto-imagen-box mb-4">
                        <img src="<?= $imgProducto ?>" alt="<?= htmlspecialchars($producto->getNombre()) ?>"
                            class="img-fluid">
                    </div>

                    <!-- 1. HAMBURGUESAS: INGREDIENTES INCLUIDOS -->
                    <?php if ($categoriaId === 1 && !empty($ingredientesBase)): ?>
                        <div class="bloque-ingredientes mb-4" data-modo="burger">
                            <h3 class="titulo-seccion-ing">INGREDIENTES INCLUIDOS</h3>
                            <div class="grid-ingredientes">
                                <?php foreach ($ingredientesBase as $ing): ?>
                                    <?php
                                    $ingId = (int) $ing['id'];
                                    $cantBase = (int) $ing['cantidad'];
                                    $obligatorio = (int) $ing['obligatorio'] === 1;

                                    if ($ingId >= 1 && $ingId <= 8) {
                                        // Pan: fijo en su cantidad base (2 tapas)
                                        $minPermitido = $cantBase;
                                        $maxPermitido = $cantBase;
                                    } elseif ($obligatorio) {
                                        // Carne / proteína: mínimo su base (1, 2 o 3) y máx 4
                                        $minPermitido = $cantBase;
                                        $maxPermitido = max($cantBase, 4);
                                    } else {
                                        // Resto: se pueden quitar a 0 y subir hasta 4
                                        $minPermitido = 0;
                                        $maxPermitido = max($cantBase, 4);
                                    }

                                    $valorActual = isset($cantidadesGuardadas[$ingId]) ? (int) $cantidadesGuardadas[$ingId] : $cantBase;
                                    $esFijo = ($minPermitido === $maxPermitido);
                                    ?>
                                    <div class="ing-pill ingrediente-item" data-id="<?= $ingId ?>"
                                        data-nombre="<?= htmlspecialchars($ing['nombre']) ?>" data-base="<?= $cantBase ?>"
                                        data-precio="<?= (float) $ing['precio_extra'] ?>" data-min="<?= $minPermitido ?>"
                                        data-max="<?= $maxPermitido ?>">

                                        <div class="ing-info">
                                            <span class="ing-nombre"><?= htmlspecialchars($ing['nombre']) ?></span>
                                            <span class="ing-subtexto">
                                                <?php if ($esFijo): ?>
                                                    Incluido (fijo)
                                                <?php else: ?>
                                                    <?= $minPermitido > 0 ? 'Mín. ' . $minPermitido : 'Opcional' ?> · Extra
                                                    +<?= number_format($ing['precio_extra'], 2, ',', '.') ?> €
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="ing-controles">
                                            <button type="button" class="btn-qty btn-ing-menos" <?= $esFijo ? 'disabled style="opacity:0.3; cursor:not-allowed;"' : '' ?>>-</button>
                                            <input type="number" name="cantidades[<?= $ingId ?>]" value="<?= $valorActual ?>"
                                                min="<?= $minPermitido ?>" max="<?= $maxPermitido ?>" readonly
                                                class="input-qty input-cantidad-ing">
                                            <button type="button" class="btn-qty btn-ing-mas" <?= $esFijo ? 'disabled style="opacity:0.3; cursor:not-allowed;"' : '' ?>>+</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 2. COMPLEMENTOS: SALSAS (1ª GRATIS, RESTO EXTRA) -->
                    <?php if ($categoriaId === 2 && !empty($salsas)): ?>
                        <div class="bloque-ingredientes mb-4" data-modo="complemento">
                            <h3 class="titulo-seccion-ing">ELIGE TUS SALSAS (1ª GRATIS)</h3>
                            <div class="grid-ingredientes">
                                <?php foreach ($salsas as $salsa): ?>
                                    <?php
                                    $salsaId = (int) $salsa->id;
                                    $valorActual = isset($cantidadesGuardadas[$salsaId]) ? (int) $cantidadesGuardadas[$salsaId] : 0;
                                    ?>
                                    <div class="ing-pill ingrediente-item" data-id="<?= $salsaId ?>"
                                        data-nombre="<?= htmlspecialchars($salsa->nombre) ?>" data-base="0"
                                        data-precio="<?= (float) $salsa->precio_extra ?>" data-min="0" data-max="4">

                                        <div class="ing-info">
                                            <span class="ing-nombre"><?= htmlspecialchars($salsa->nombre) ?></span>
                                            <span class="ing-subtexto">+<?= number_format($salsa->precio_extra, 2, ',', '.') ?>
                                                € (1ª gratis)</span>
                                        </div>

                                        <div class="ing-controles">
                                            <button type="button" class="btn-qty btn-ing-menos">-</button>
                                            <input type="number" name="cantidades[<?= $salsaId ?>]" value="<?= $valorActual ?>"
                                                min="0" max="4" readonly class="input-qty input-cantidad-ing">
                                            <button type="button" class="btn-qty btn-ing-mas">+</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 3. POSTRES (HELADO BORN): HASTA 2 BOLAS INCLUIDAS -->
                    <?php if ($categoriaId === 3 && !empty($ingredientesBase)): ?>
                        <div class="bloque-ingredientes mb-4" data-modo="postre" data-max-total="2">
                            <h3 class="titulo-seccion-ing">ELIGE TUS SABORES (MÁX. 2 BOLAS)</h3>
                            <div class="grid-ingredientes">
                                <?php foreach ($ingredientesBase as $sabor): ?>
                                    <?php
                                    $saborId = (int) $sabor['id'];
                                    $valorActual = isset($cantidadesGuardadas[$saborId]) ? (int) $cantidadesGuardadas[$saborId] : 0;
                                    ?>
                                    <div class="ing-pill ingrediente-item" data-id="<?= $saborId ?>"
                                        data-nombre="<?= htmlspecialchars($sabor['nombre']) ?>" data-base="0" data-precio="0"
                                        data-min="0" data-max="2">

                                        <div class="ing-info">
                                            <span class="ing-nombre"><?= htmlspecialchars($sabor['nombre']) ?></span>
                                            <span class="ing-subtexto">Incluido (máx. 2 bolas)</span>
                                        </div>

                                        <div class="ing-controles">
                                            <button type="button" class="btn-qty btn-ing-menos">-</button>
                                            <input type="number" name="cantidades[<?= $saborId ?>]" value="<?= $valorActual ?>"
                                                min="0" max="2" readonly class="input-qty input-cantidad-ing">
                                            <button type="button" class="btn-qty btn-ing-mas">+</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- COLUMNA DERECHA: INFO PRODUCTO + RESUMEN -->
                <div class="col-lg-6">
                    <div class="producto-cabecera mb-4">
                        <h1 class="producto-titulo"><?= htmlspecialchars($producto->getNombre()) ?></h1>
                        <div class="producto-precio-cabecera"><?= number_format($precioBase, 2, ',', '.') ?> €</div>
                        <p class="producto-descripcion"><?= htmlspecialchars($producto->getDescripcion()) ?></p>
                    </div>

                    <!-- CAJA OSCURA SIN BORDE: RESUMEN DEL PRODUCTO -->
                    <div class="resumen-card" id="resumen-producto" data-precio-base="<?= $precioBase ?>">
                        <h2 class="resumen-titulo">RESUMEN DEL PRODUCTO</h2>

                        <div class="resumen-linea">
                            <span>Precio Base</span>
                            <span><?= number_format($precioBase, 2, ',', '.') ?> €</span>
                        </div>

                        <div class="resumen-extras-bloque">
                            <span class="resumen-subtitulo">Personalización</span>
                            <div id="lista-extras-resumen" class="lista-extras-resumen">
                                <div class="resumen-linea text-muted-custom">
                                    <span>Sin modificaciones</span>
                                    <span>0,00 €</span>
                                </div>
                            </div>
                        </div>

                        <hr class="resumen-separador">

                        <div class="resumen-linea">
                            <span>Subtotal (sin IVA)</span>
                            <span id="resumen-sin-iva">0,00 €</span>
                        </div>

                        <div class="resumen-linea">
                            <span>IVA (10%)</span>
                            <span id="resumen-iva">0,00 €</span>
                        </div>

                        <hr class="resumen-separador">

                        <div class="resumen-linea resumen-total-linea">
                            <span>Total</span>
                            <span id="precio-total"><?= number_format($precioBase, 2, ',', '.') ?> €</span>
                        </div>

                        <!-- SELECTOR UNIDADES DEL PRODUCTO -->
                        <div class="producto-cantidad-bar mt-4 mb-3">
                            <button type="button" class="btn-prod-qty" id="btn-prod-menos">-</button>
                            <input type="number" name="cantidad_producto" id="cantidad-producto"
                                value="<?= (int) $cantidadProductoInicial ?>" min="1" max="20" readonly
                                class="input-prod-qty">
                            <button type="button" class="btn-prod-qty" id="btn-prod-mas">+</button>
                        </div>

                        <button type="submit" class="btn-anadir-carrito">
                            AÑADIR AL CARRITO
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</section>

<script src="js/personalizar.js"></script>