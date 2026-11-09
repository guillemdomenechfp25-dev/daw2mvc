<?php
include_once("daos/ProductoDAO.php");

class PersonalizarController
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $seleccionados = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
        $view = "views/personalizar.php";
        include_once 'views/main.php';
    }
}