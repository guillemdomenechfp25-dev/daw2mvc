<?php
include_once("daos/ProductoDAO.php");
include_once("daos/CategoriaDAO.php");

class CartaController
{
    public function index()
    {
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

    public function api()
    {
        $metodo = $_SERVER['REQUEST_METHOD'];
        if ($metodo == "GET") {
            $productos = ProductoDAO::getAllJson();
            echo json_encode($productos);
        } else if ($metodo == "POST") {

        }
    }
}