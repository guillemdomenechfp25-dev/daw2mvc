<?php
include_once("daos/ProductoDAO.php");

class PostreDAO extends ProductoDAO
{
    public static function getAll()
    {
        return parent::getByCategoria(3);
    }
}