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
    public function carta()
    {
        $productos = ProductoDAO::getAll();
        $view = "views/carta.php";
        include_once 'views/main.php';
    }


}
