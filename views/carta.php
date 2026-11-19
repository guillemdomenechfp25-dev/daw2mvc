<link rel="stylesheet" href="css/carta.css">

<section class="carta-section py-5">
    <div class="container-fluid header-container text-center">

        <!-- ENCABEZADO -->
        <div class="mb-4">
            <h1 class="titulo-carta text-uppercase fw-bold">Nuestra Carta</h1>
            <p class="subtitulo-carta">Descubre la ingeniería gastronómica de CUPRA</p>
        </div>

        <!-- 1. BOTONERA DE FILTROS (SIEMPRE VISIBLE) -->
        <div class="d-flex justify-content-center flex-wrap gap-2 mb-5">
            <a href="index.php?controller=carta&action=index"
                class="btn-filtro <?= !isset($_GET['categoria']) ? 'activo' : '' ?>">
                TODO
            </a>

            <?php if (!empty($categorias) && is_array($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <?php $esActivo = isset($_GET['categoria']) && $_GET['categoria'] == $cat->getId(); ?>
                    <a href="index.php?controller=carta&action=index&categoria=<?= $cat->getId() ?>"
                        class="btn-filtro <?= $esActivo ? 'activo' : '' ?>">
                        <?= strtoupper($cat->getNombre()) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- 2. BLOQUE DE PRODUCTOS -->
        <div class="mb-5">
            <?php if (isset($_GET['categoria']) && !empty($_GET['categoria'])): ?>

                <!-- CASO A: CATEGORÍA SELECCIONADA -->
                <div class="destacados-grid">
                    <?php if (!empty($productos) && is_array($productos)): ?>
                        <?php foreach ($productos as $prod): ?>
                            <?php $img = !empty($prod->getImagen()) ? htmlspecialchars($prod->getImagen()) : 'img/home/banner.png'; ?>
                            <article class="product-card">
                                <div class="card-img-wrapper">
                                    <img src="<?= $img ?>" alt="<?= htmlspecialchars($prod->getNombre()) ?>" class="product-img">
                                </div>
                                <div class="card-glass-content">
                                    <div class="product-info">
                                        <h3 class="product-name">
                                            <?= strtoupper(htmlspecialchars($prod->getNombre())) ?>
                                        </h3>
                                        <span class="product-price">
                                            <?= number_format($prod->getPrecio(), 2, ',', '.') ?> €
                                        </span>
                                    </div>
                                    <div class="card-actions">
                                        <a href="index.php?controller=carrito&action=add&id=<?= $prod->getId() ?>"
                                            class="btn-add text-decoration-none">
                                            AÑADIR AL CARRITO
                                        </a>
                                        <a href="index.php?controller=carrito&action=personalizar&id=<?= $prod->getId() ?>"
                                            class="btn-edit text-decoration-none" title="Personalizar / Editar">&#9881;</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="w-100 py-5 text-center">
                            <p class="text-white opacity-50">No hay productos en esta categoría.</p>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <!-- CASO B: VISTA "TODO" AGRUPADA POR CATEGORÍA -->
                <?php if (!empty($categorias) && is_array($categorias)): ?>
                    <?php foreach ($categorias as $cat): ?>

                        <!-- Título de la categoría actual -->
                        <div class="text-start mt-5 mb-4 border-bottom border-secondary pb-2">
                            <h2 class="text-uppercase text-white fw-bold m-0">
                                <?= htmlspecialchars($cat->getNombre()) ?>
                            </h2>
                        </div>

                        <!-- Productos pertenecientes a esta categoría -->
                        <div class="destacados-grid">
                            <?php
                            $tieneProductos = false;
                            foreach ($productos as $prod):
                                if ($prod->getCategoriaId() == $cat->getId()):
                                    $tieneProductos = true;
                                    $img = !empty($prod->getImagen()) ? htmlspecialchars($prod->getImagen()) : 'img/home/banner.png';
                                    ?>
                                    <article class="product-card">
                                        <div class="card-img-wrapper">
                                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($prod->getNombre()) ?>" class="product-img">
                                        </div>
                                        <div class="card-glass-content">
                                            <div class="product-info">
                                                <h3 class="product-name">
                                                    <?= strtoupper(htmlspecialchars($prod->getNombre())) ?>
                                                </h3>
                                                <span class="product-price">
                                                    <?= number_format($prod->getPrecio(), 2, ',', '.') ?> €
                                                </span>
                                            </div>
                                            <div class="card-actions">
                                                <a href="index.php?controller=carrito&action=add&id=<?= $prod->getId() ?>"
                                                    class="btn-add text-decoration-none">
                                                    AÑADIR AL CARRITO
                                                </a>
                                                <a href="index.php?controller=carrito&action=personalizar&id=<?= $prod->getId() ?>"
                                                    class="btn-edit text-decoration-none" title="Personalizar / Editar">&#9881;</a>
                                            </div>
                                        </div>
                                    </article>
                                    <?php
                                endif;
                            endforeach;

                            if (!$tieneProductos): ?>
                                <p class="text-white opacity-50 text-start small">No hay productos en esta sección.</p>
                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>

            <?php endif; ?>
        </div>

    </div>
</section>