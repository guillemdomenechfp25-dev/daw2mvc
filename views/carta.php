<link rel="stylesheet" href="/Daw2mvc/css/carta.css">

<section class="carta-section py-5">
    <div class="container text-center">

        <!-- Título y Subtítulo -->
        <div class="mb-4">
            <h1 class="titulo-carta text-uppercase fw-bold">Nuestra Carta</h1>
            <p class="subtitulo-carta">Descubre la ingeniería gastronómica de CUPRA</p>
        </div>

        <!-- Filtros Dinámicos -->
        <div class="d-flex justify-content-center flex-wrap gap-2 mb-5">

            <a href="index.php?controller=producto&action=carta"
                class="btn-filtro <?= !isset($_GET['categoria']) ? 'activo' : '' ?>">
                TODO
            </a>

            <?php if (!empty($categorias) && is_array($categorias)): ?>
                <?php foreach ($categorias as $cat): ?>
                    <?php $esActivo = isset($_GET['categoria']) && $_GET['categoria'] == $cat->getId(); ?>
                    <a href="index.php?controller=producto&action=carta&categoria=<?= $cat->getId() ?>"
                        class="btn-filtro <?= $esActivo ? 'activo' : '' ?>">
                        <?= strtoupper($cat->getNombre()) ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <!-- GRID DE PRODUCTOS (EXACTO A LA CAPTURA) -->
        <div class="destacados-grid">
            <?php if (!empty($productos) && is_array($productos)): ?>
                <?php foreach ($productos as $prod): ?>
                    <?php
                    $img = !empty($prod->getImagen()) ? htmlspecialchars($prod->getImagen()) : 'img/home/banner.png';
                    ?>
                    <article class="product-card">

                        <div class="card-img-wrapper">
                            <img src="<?= $img ?>" alt="<?= htmlspecialchars($prod->getNombre()) ?>" class="product-img">
                        </div>

                        <div class="card-glass-content">
                            <div class="product-info">
                                <h3 class="product-name"><?= strtoupper(htmlspecialchars($prod->getNombre())) ?></h3>
                                <span class="product-price"><?= number_format($prod->getPrecio(), 2, ',', '.') ?> €</span>
                            </div>

                            <div class="card-actions">
                                <button class="btn-add">AÑADIR AL CARRITO</button>
                                <button class="btn-edit" title="Personalizar / Editar">&#9881;</button>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="w-100 py-5 text-center">
                    <p class="text-white opacity-50">No hay productos disponibles en esta categoría.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>