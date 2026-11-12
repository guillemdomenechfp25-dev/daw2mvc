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
}