<link rel="stylesheet" href="css/perfil.css">

<section class="container-fluid d-flex align-items-start justify-content-center py-5 perfil-section">
    <div class="p-4 p-md-5 perfil-card">
        <h2 class="text-center text-uppercase fw-bold mb-4">Mi Perfil</h2>

        <!-- PESTAÑAS -->
        <ul class="nav nav-pills justify-content-center gap-2 mb-4 perfil-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-datos-btn" data-bs-toggle="pill" data-bs-target="#tab-datos"
                    type="button" role="tab">
                    Mis Datos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-pedidos-btn" data-bs-toggle="pill" data-bs-target="#tab-pedidos"
                    type="button" role="tab">
                    Mis Pedidos (<?= count($pedidos ?? []) ?>)
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- PESTAÑA 1: DATOS DEL USUARIO -->
            <div class="tab-pane fade show active" id="tab-datos" role="tabpanel">
                <div class="d-grid gap-3 mb-4">
                    <div class="perfil-dato">
                        <span class="perfil-label">Nombre</span>
                        <span class="perfil-valor fw-semibold"><?= htmlspecialchars($usuario['nombre']) ?></span>
                    </div>

                    <div class="perfil-dato">
                        <span class="perfil-label">Email</span>
                        <span class="perfil-valor"><?= htmlspecialchars($usuario['email']) ?></span>
                    </div>

                    <div class="perfil-dato">
                        <span class="perfil-label">Teléfono</span>
                        <span class="perfil-valor"><?= htmlspecialchars($usuario['telefono']) ?></span>
                    </div>

                    <div class="perfil-dato">
                        <span class="perfil-label">Dirección habitual</span>
                        <span class="perfil-valor"><?= htmlspecialchars($usuario['direccion']) ?></span>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA 2: HISTORIAL DE PEDIDOS -->
            <div class="tab-pane fade" id="tab-pedidos" role="tabpanel">
                <?php if (empty($pedidos)): ?>
                    <div class="text-center py-4 text-secondary small">
                        Aún no has realizado ningún pedido.
                    </div>
                <?php else: ?>
                    <div class="d-grid gap-3 mb-4">
                        <?php foreach ($pedidos as $ped): ?>
                            <?php
                            $codigo = '#CUP-' . str_pad((int) $ped['id'], 4, '0', STR_PAD_LEFT);
                            $fecha = date('d/m/Y H:i', strtotime($ped['fecha_pedido']));
                            ?>
                            <div class="perfil-pedido-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="perfil-pedido-codigo"><?= $codigo ?></span>
                                        <span class="small text-secondary ms-2"><?= $fecha ?></span>
                                    </div>
                                    <span class="badge bg-dark border border-secondary text-uppercase">
                                        <?= htmlspecialchars($ped['estado']) ?>
                                    </span>
                                </div>

                                <div class="small text-secondary mb-2">
                                    <?= $ped['tipo_entrega'] === 'domicilio' ? 'A domicilio' : 'Recogida en local' ?>
                                    &middot; Pago: <?= htmlspecialchars($ped['metodo_pago']) ?>
                                </div>

                                <!-- Productos del pedido -->
                                <div class="border-top border-secondary pt-2 mt-2 small">
                                    <?php foreach ($ped['detalles'] as $det): ?>
                                        <div class="d-flex justify-content-between py-1">
                                            <span><?= (int) $det['cantidad'] ?>x
                                                <?= htmlspecialchars($det['nombre_producto']) ?></span>
                                            <span class="text-secondary"><?= number_format($det['subtotal'], 2, ',', '.') ?>
                                                €</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="d-flex justify-content-between fw-bold border-top border-secondary pt-2 mt-2">
                                    <span>TOTAL</span>
                                    <span class="perfil-pedido-codigo"><?= number_format($ped['total'], 2, ',', '.') ?> €</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-grid gap-2">
            <a href="index.php?controller=carta&action=index" class="btn py-2 perfil-btn-principal">
                Volver a la carta
            </a>
            <a href="index.php?controller=usuario&action=logout"
                class="btn btn-outline-danger py-2 fw-bold text-uppercase">
                Cerrar sesión
            </a>
        </div>
    </div>
</section>