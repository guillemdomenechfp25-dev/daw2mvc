<?php
include_once("daos/ProductoDAO.php");

class HamburguesaDAO extends ProductoDAO
{
    public static function getAll()
    {
        return parent::getByCategoria(1);
    }
}