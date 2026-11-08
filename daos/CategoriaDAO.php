<?php
include_once("config/dataBase.php");
include_once("models/Categoria.php");

class CategoriaDAO
{
    public static function getAllActivas()
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM categorias WHERE activa = 1 ORDER BY id ASC");
        $stmt->execute();
        $result = $stmt->get_result();

        $categorias = [];
        while ($categoria = $result->fetch_object("Categoria")) {
            $categorias[] = $categoria;
        }

        $stmt->close();
        $con->close();

        return $categorias;
    }
}