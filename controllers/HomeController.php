<?php
include_once("daos/ProductoDAO.php");

class HomeController
{
    public function index()
    {
        $productos = ProductoDAO::getAll();
        $view = "views/home.php";
        include_once 'views/main.php';
    }
}