<?php

include_once("daos/ProductoDAO.php");
include_once("models/Producto.php");

class productoController
{
    public function index()
    {
        $productos = ProductoDAO::getAll();
        $view = "views/home.php";
        include_once 'views/main.php';
    }
    public function show()
    {
        if (!isset($_GET['id']) || !ctype_digit((string) $_GET['id'])) {
            header('Location:?controller=producto');
            exit;
        }

        $producto = ProductoDAO::getById((int) $_GET['id']);
        if ($producto === null) {
            header('Location:?controller=producto');
            exit;
        }

        $view = "views/productos/show.php";
        include_once 'views/main.php';
    }


}
