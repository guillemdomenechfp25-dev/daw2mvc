<?php

include_once("daos/ProductoDAO.php");
include_once("daos/CategoriaDAO.php");
include_once("models/Producto.php");
include_once("models/Categoria.php");

class productoController
{
    public function index()
    {
        $productos = ProductoDAO::getAll();
        $view = "views/home.php";
        include_once 'views/main.php';
    }

    public function carta()
    {
        // 1. Cargamos datos
        $categorias = CategoriaDAO::getAllActivas();

        if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
            $categoria_id = (int) $_GET['categoria'];
            $productos = ProductoDAO::getByCategoria($categoria_id);
        } else {
            $productos = ProductoDAO::getAll();
        }

        $view = "views/carta.php";
        include_once 'views/main.php';
    }


}
