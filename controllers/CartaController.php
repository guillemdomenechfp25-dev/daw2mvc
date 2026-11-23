<?php
include_once("daos/ProductoDAO.php");
include_once("daos/HamburguesaDAO.php");
include_once("daos/ComplementoDAO.php");
include_once("daos/PostreDAO.php");
include_once("daos/BebidaDAO.php");
include_once("daos/CategoriaDAO.php");

class CartaController
{
    public function index()
    {
        $categorias = CategoriaDAO::getAllActivas();

        if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
            $categoria_id = (int) $_GET['categoria'];

            switch ($categoria_id) {
                case 1:
                    $productos = HamburguesaDAO::getAll();
                    break;
                case 2:
                    $productos = ComplementoDAO::getAll();
                    break;
                case 3:
                    $productos = PostreDAO::getAll();
                    break;
                case 4:
                    $productos = BebidaDAO::getAll();
                    break;
                default:
                    $productos = ProductoDAO::getByCategoria($categoria_id);
                    break;
            }

        } else {
            $productos = ProductoDAO::getAll();

        }

        $personalizables = ProductoDAO::getIdsPersonalizables();
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