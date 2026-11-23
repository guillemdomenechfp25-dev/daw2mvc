<?php
include_once("config/dataBase.php");

class PedidoDAO
{
    public static function crearPedido($usuarioId, $carrito, $entrega, $metodoPago)
    {
        $con = DataBase::connect();
        $con->begin_transaction();

        try {
            $subtotal = 0;
            foreach ($carrito as $item) {
                $subtotal += $item['precio'] * $item['cantidad'];
            }

            $descuento = 0.00;
            $gastosEnvio = (float) ($entrega['gastos_envio'] ?? 0);
            $total = $subtotal - $descuento + $gastosEnvio;

            $estado = 'confirmado';
            $estadoPago = ($metodoPago === 'tarjeta') ? 'pagado' : 'pendiente';
            $tipoEntrega = $entrega['tipo_entrega'] ?? 'domicilio';
            $direccionEntrega = $entrega['direccion_entrega'] ?? '';
            $ciudadEntrega = $entrega['ciudad_entrega'] ?? '';
            $codigoPostal = $entrega['codigo_postal'] ?? '';

            // 1. Insertar cabecera en pedidos
            $sqlPedido = "INSERT INTO pedidos 
                (usuario_id, estado, subtotal, descuento, gastos_envio, total, metodo_pago, estado_pago, tipo_entrega, direccion_entrega, ciudad_entrega, codigo_postal)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtPedido = $con->prepare($sqlPedido);
            $stmtPedido->bind_param(
                "isddddssssss",
                $usuarioId,
                $estado,
                $subtotal,
                $descuento,
                $gastosEnvio,
                $total,
                $metodoPago,
                $estadoPago,
                $tipoEntrega,
                $direccionEntrega,
                $ciudadEntrega,
                $codigoPostal
            );
            $stmtPedido->execute();
            $pedidoId = $con->insert_id;
            $stmtPedido->close();

            // 2. Insertar cada línea en pedido_detalle
            $sqlDetalle = "INSERT INTO pedido_detalle 
                (pedido_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal)
                VALUES (?, ?, ?, ?, ?, ?)";

            $stmtDetalle = $con->prepare($sqlDetalle);

            foreach ($carrito as $item) {
                $productoId = (int) $item['producto_id'];
                $nombreProducto = $item['nombre'];
                if (!empty($item['ingredientes'])) {
                    $nombreProducto .= " (" . $item['ingredientes'] . ")";
                }
                $precioUnitario = (float) $item['precio'];
                $cantidad = (int) $item['cantidad'];
                $subtotalLinea = $precioUnitario * $cantidad;

                $stmtDetalle->bind_param(
                    "iisdid",
                    $pedidoId,
                    $productoId,
                    $nombreProducto,
                    $precioUnitario,
                    $cantidad,
                    $subtotalLinea
                );
                $stmtDetalle->execute();
            }

            $stmtDetalle->close();

            $con->commit();
            $con->close();

            return $pedidoId;
        } catch (Exception $e) {
            $con->rollback();
            $con->close();
            return null;
        }
    }

    public static function getByUsuarioId($usuarioId)
    {
        $con = DataBase::connect();

        $stmt = $con->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC, id DESC");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos = [];
        while ($pedido = $result->fetch_assoc()) {
            $stmtDet = $con->prepare("SELECT * FROM pedido_detalle WHERE pedido_id = ? ORDER BY id ASC");
            $stmtDet->bind_param("i", $pedido['id']);
            $stmtDet->execute();
            $resDet = $stmtDet->get_result();

            $detalles = [];
            while ($linea = $resDet->fetch_assoc()) {
                $detalles[] = $linea;
            }
            $stmtDet->close();

            $pedido['detalles'] = $detalles;
            $pedidos[] = $pedido;
        }

        $stmt->close();
        $con->close();

        return $pedidos;
    }
}