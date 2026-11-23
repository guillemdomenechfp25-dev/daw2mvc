<?php
include_once("daos/ProductoDAO.php");

class ComplementoDAO extends ProductoDAO
{
    public static function getAll()
    {
        return parent::getByCategoria(2);
    }
}