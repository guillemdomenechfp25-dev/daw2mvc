<?php
include_once("daos/ProductoDAO.php");

class CarritoController
{
    public function index()
    {
        $view = "views/carrito.php";
        include_once 'views/main.php';
    }

    public function add()
    {
        if (isset($_GET['id'])) {
            $producto_id = (int) $_GET['id'];

            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = array();
            }

            if (isset($_SESSION['carrito'][$producto_id])) {
                $_SESSION['carrito'][$producto_id]['cantidad']++;
            } else {
                $producto = ProductoDAO::getById($producto_id);

                if ($producto) {
                    $ingredientes = ProductoDAO::getIngredientesTexto($producto_id);
                    $_SESSION['carrito'][$producto_id] = array(
                        "id" => $producto->getId(),
                        "producto_id" => $producto->getId(),
                        "nombre" => $producto->getNombre(),
                        "descripcion" => $producto->getDescripcion(),
                        "ingredientes" => $ingredientes,
                        "precio" => $producto->getPrecio(),
                        "cantidad" => 1,
                        "imagen" => $producto->getImagen()
                    );
                }
            }
        }

        // Una vez guardado en la sesión, volvemos a la carta
        header("Location: index.php?controller=carta&action=index");
        exit;
    }

    public function personalizar()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $producto = ProductoDAO::getById($id);

        if (!$producto) {
            header("Location: index.php?controller=carta&action=index");
            exit;
        }

        $ingredientesBase = ProductoDAO::getIngredientesBase($id);
        $ingredientesExtras = ProductoDAO::getIngredientesExtras($id);

        $seleccionados_base = $_SESSION['carrito'][$id]['base_ids'] ?? array_column($ingredientesBase, 'id');
        $seleccionados_extras = $_SESSION['carrito'][$id]['extra_ids'] ?? [];

        $view = "views/personalizar.php";
        include_once 'views/main.php';
    }

    public function guardarPersonalizacion()
    {
        $id = (int) ($_POST['producto_id'] ?? 0);
        $producto = ProductoDAO::getById($id);

        if (!$producto) {
            header("Location: index.php?controller=carta&action=index");
            exit;
        }

        $cantidades = $_POST['cantidades'] ?? [];
        $cantidadProducto = max(1, (int) ($_POST['cantidad_producto'] ?? 1));

        $baseMap = [];
        foreach (ProductoDAO::getIngredientesBase($id) as $b) {
            $baseMap[(int) $b['id']] = (int) $b['cantidad'];
        }

        $precioFinal = (float) $producto->getPrecio();
        $resumen = [];

        foreach ($cantidades as $ingId => $cant) {
            $ingId = (int) $ingId;
            $cant = (int) $cant;
            $ing = ProductoDAO::getIngredientesPorIds($ingId);
            if (!$ing) {
                continue;
            }

            $cantBase = $baseMap[$ingId] ?? 0;

            if ($cantBase > 0 && $cant === 0) {
                $resumen[] = "Sin " . $ing['nombre'];
            } elseif ($cantBase > 0 && $cant <= $cantBase) {
                $resumen[] = $ing['nombre'] . ($cant > 1 ? " (x$cant)" : "");
            } elseif ($cant > $cantBase) {
                $extras = $cant - $cantBase;
                $precioFinal += $extras * (float) $ing['precio_extra'];
                $resumen[] = ($cantBase === 0 ? "+ " : "") . $ing['nombre'] . ($cant > 1 ? " (x$cant)" : "");
            }
        }

        $_SESSION['carrito'][$id] = [
            "id" => $producto->getId(),
            "producto_id" => $producto->getId(),
            "nombre" => $producto->getNombre(),
            "descripcion" => $producto->getDescripcion(),
            "ingredientes" => implode(' - ', $resumen),
            "cantidades" => $cantidades,
            "precio" => $precioFinal,
            "cantidad" => $cantidadProducto,
            "imagen" => $producto->getImagen()
        ];

        header("Location: index.php?controller=carrito&action=index");
        exit;
    }

    public function eliminar()
    {
        unset($_SESSION['carrito'][$_GET['id'] ?? '']);
        header("Location: index.php?controller=carrito&action=index");
        exit;
    }

    public function actualizarCantidad()
    {

    }
}