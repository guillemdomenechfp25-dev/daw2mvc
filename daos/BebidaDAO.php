<?php
include_once("daos/ProductoDAO.php");

class BebidaDAO extends ProductoDAO
{
    public static function getAll()
    {
        return parent::getByCategoria(4);
    }
}