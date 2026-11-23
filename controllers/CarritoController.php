<?php
include_once("daos/ProductoDAO.php");
include_once("daos/PedidoDAO.php");

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

            $claveCarrito = $producto_id . '_base';

            if (isset($_SESSION['carrito'][$claveCarrito])) {
                $_SESSION['carrito'][$claveCarrito]['cantidad']++;
            } else {
                $producto = ProductoDAO::getById($producto_id);

                if ($producto) {
                    $ingredientes = ProductoDAO::getIngredientesTexto($producto_id);
                    $_SESSION['carrito'][$claveCarrito] = array(
                        "id" => $claveCarrito,
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
        $claveEditando = $_GET['clave'] ?? '';
        $producto = ProductoDAO::getById($id);

        if (!$producto) {
            header("Location: index.php?controller=carta&action=index");
            exit;
        }

        $ingredientesBase = ProductoDAO::getIngredientesBase($id);
        $salsas = ((int) $producto->getCategoriaId() === 2) ? ProductoDAO::getSalsas() : [];

        $view = "views/personalizar.php";
        include_once 'views/main.php';
    }

    public function guardarPersonalizacion()
    {
        $id = (int) ($_POST['producto_id'] ?? 0);
        $claveAnterior = $_POST['clave_editando'] ?? '';
        $producto = ProductoDAO::getById($id);

        if (!$producto) {
            header("Location: index.php?controller=carta&action=index");
            exit;
        }

        $categoriaId = (int) $producto->getCategoriaId();
        $cantidades = $_POST['cantidades'] ?? [];
        $cantidadProducto = max(1, (int) ($_POST['cantidad_producto'] ?? 1));

        $baseMap = [];
        foreach (ProductoDAO::getIngredientesBase($id) as $b) {
            $baseMap[(int) $b['id']] = (int) $b['cantidad'];
        }

        $precioFinal = (float) $producto->getPrecio();
        $resumen = [];
        $salsasGratisRestantes = ($categoriaId === 2) ? 1 : 0;
        $esModificado = false;

        foreach ($cantidades as $ingId => $cant) {
            $ingId = (int) $ingId;
            $cant = (int) $cant;
            $ing = ProductoDAO::getIngredientesPorIds($ingId);
            if (!$ing) {
                continue;
            }

            // CASO POSTRES (HELADO BORN): hasta 2 bolas incluidas en el precio
            if ($categoriaId === 3) {
                if ($cant > 0) {
                    $esModificado = true;
                    $resumen[] = $ing['nombre'] . ($cant > 1 ? " (x$cant)" : "");
                }
                continue;
            }

            // CASO COMPLEMENTOS: los ingredientes base no se modifican, solo se resumen las salsas
            if ($categoriaId === 2) {
                if ($ingId >= 30 && $ingId <= 44 && $cant > 0) {
                    $esModificado = true;
                    $salsasDePago = $cant;
                    if ($salsasGratisRestantes > 0) {
                        $descuento = min($salsasGratisRestantes, $salsasDePago);
                        $salsasDePago -= $descuento;
                        $salsasGratisRestantes -= $descuento;
                    }
                    $precioFinal += $salsasDePago * (float) $ing['precio_extra'];
                    $resumen[] = "Salsa " . $ing['nombre'] . ($cant > 1 ? " (x$cant)" : "");
                }
                continue;
            }

            // CASO HAMBURGUESAS
            $cantBase = $baseMap[$ingId] ?? 0;

            if ($cant !== $cantBase) {
                $esModificado = true;
            }

            if ($cantBase > 0 && $cant === 0) {
                $resumen[] = "Sin " . $ing['nombre'];
            } elseif ($cantBase > 0 && $cant <= $cantBase) {
                $resumen[] = $ing['nombre'] . ($cant > 1 ? " (x$cant)" : "");
            } elseif ($cant > $cantBase) {
                $extras = $cant - $cantBase;
                $precioFinal += $extras * (float) $ing['precio_extra'];
                $resumen[] = $ing['nombre'] . ($cant > 1 ? " (x$cant)" : "");
            }
        }

        $claveNueva = $esModificado ? ($id . '_' . md5(json_encode($cantidades))) : ($id . '_base');

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if ($claveAnterior !== '' && isset($_SESSION['carrito'][$claveAnterior])) {
            unset($_SESSION['carrito'][$claveAnterior]);
        }

        if (isset($_SESSION['carrito'][$claveNueva])) {
            $_SESSION['carrito'][$claveNueva]['cantidad'] += $cantidadProducto;
        } else {
            $_SESSION['carrito'][$claveNueva] = [
                "id" => $claveNueva,
                "producto_id" => $producto->getId(),
                "nombre" => $producto->getNombre() . ($esModificado ? " (Editado)" : ""),
                "descripcion" => $producto->getDescripcion(),
                "ingredientes" => implode(' - ', $resumen),
                "cantidades" => $cantidades,
                "precio" => $precioFinal,
                "cantidad" => $cantidadProducto,
                "imagen" => $producto->getImagen()
            ];
        }

        header("Location: index.php?controller=carrito&action=index");
        exit;
    }

    public function eliminar()
    {
        unset($_SESSION['carrito'][$_GET['id'] ?? '']);
        header("Location: index.php?controller=carrito&action=index");
        exit;
    }

    public function entrega()
    {
        if (empty($_SESSION['carrito'])) {
            header("Location: index.php?controller=carrito&action=index");
            exit;
        }

        if (!isset($_SESSION['usuario'])) {
            $_SESSION['redirect_after_login'] = "index.php?controller=carrito&action=entrega";
            $_SESSION['error_login_carrito'] = true;
            header("Location: index.php?controller=carrito&action=index");
            exit;
        }

        $view = "views/entrega.php";
        include_once 'views/main.php';
    }

    public function guardarEntrega()
    {
        if (empty($_SESSION['carrito']) || !isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=carrito&action=index");
            exit;
        }

        $tipoEntrega = ($_POST['tipo_entrega'] ?? 'domicilio') === 'recogida' ? 'recogida' : 'domicilio';

        $_SESSION['entrega'] = [
            'tipo_entrega' => $tipoEntrega,
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'direccion_entrega' => $tipoEntrega === 'domicilio' ? trim($_POST['direccion_entrega'] ?? '') : 'Recogida en local CUPRAEATS',
            'codigo_postal' => $tipoEntrega === 'domicilio' ? trim($_POST['codigo_postal'] ?? '') : '',
            'ciudad_entrega' => $tipoEntrega === 'domicilio' ? trim($_POST['ciudad_entrega'] ?? '') : '',
            'telefono' => trim($_POST['telefono'] ?? ''),
            'gastos_envio' => $tipoEntrega === 'domicilio' ? 4.90 : 0.00
        ];

        header("Location: index.php?controller=carrito&action=pago");
        exit;
    }

    public function pago()
    {
        if (empty($_SESSION['carrito']) || !isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=carrito&action=index");
            exit;
        }

        if (empty($_SESSION['entrega'])) {
            header("Location: index.php?controller=carrito&action=entrega");
            exit;
        }

        $view = "views/pago.php";
        include_once 'views/main.php';
    }

    public function confirmarPedido()
    {
        if (empty($_SESSION['carrito']) || !isset($_SESSION['usuario']) || empty($_SESSION['entrega'])) {
            header("Location: index.php?controller=carrito&action=index");
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario']['id'];
        $carrito = $_SESSION['carrito'];
        $entrega = $_SESSION['entrega'];
        $metodoPago = ($_POST['metodo_pago'] ?? 'tarjeta') === 'efectivo' ? 'efectivo' : 'tarjeta';

        $pedidoId = PedidoDAO::crearPedido($usuarioId, $carrito, $entrega, $metodoPago);

        if (!$pedidoId) {
            header("Location: index.php?controller=carrito&action=pago");
            exit;
        }

        $subtotal = 0;
        foreach ($carrito as $item) {
            $subtotal += $item['precio'] * $item['cantidad'];
        }
        $gastosEnvio = (float) ($entrega['gastos_envio'] ?? 0);

        $_SESSION['ultimo_pedido'] = [
            'id' => $pedidoId,
            'productos' => $carrito,
            'entrega' => $entrega,
            'metodo_pago' => $metodoPago,
            'subtotal' => $subtotal,
            'gastos_envio' => $gastosEnvio,
            'total' => $subtotal + $gastosEnvio
        ];

        unset($_SESSION['carrito']);
        unset($_SESSION['entrega']);

        header("Location: index.php?controller=carrito&action=confirmacion");
        exit;
    }

    public function confirmacion()
    {
        if (empty($_SESSION['ultimo_pedido'])) {
            header("Location: index.php?controller=carta&action=index");
            exit;
        }

        $pedido = $_SESSION['ultimo_pedido'];
        $view = "views/confirmacion.php";
        include_once 'views/main.php';
    }

    public function actualizarCantidad()
    {

    }
}