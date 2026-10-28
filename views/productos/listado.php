<h1>Carta de CUPRAEATS (Modo Prueba)</h1>

<table>
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Acciones</th>
    </tr>
    <?php if (empty($productos)) { ?>
        <tr>
            <td colspan="4">No hay platos en la carta todavía.</td>
        </tr>
    <?php } ?>
    <?php foreach ($productos as $producto) { ?>
        <tr>
            <td><?= htmlspecialchars($producto->getNombre(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($producto->getDescripcion(), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= number_format((float) $producto->getPrecio(), 2, ',', '.') ?> €</td>
            <td>
                <a href="?controller=producto&action=show&id=<?= (int) $producto->getId() ?>">Ver detalle</a>
            </td>
        </tr>
    <?php } ?>
</table>