<?php
include_once("config/dataBase.php");
include_once("models/Producto.php");


class ProductoDAO
{
    public static function getAll()
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM productos WHERE activo = 1 ORDER BY categoria_id ASC, id ASC");
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_object("Producto")) {
            $productos[] = $producto;
        }
        $stmt->close();
        $con->close();

        return $productos;
    }

    public static function getByCategoria($categoria_id)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM productos WHERE categoria_id = ? AND activo = 1 ORDER BY id ASC");
        $stmt->bind_param("i", $categoria_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_object("Producto")) {
            $productos[] = $producto;
        }
        $stmt->close();
        $con->close();

        return $productos;
    }

    public static function getDestacados()
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM productos WHERE destacado = 1 AND activo = 1");
        $stmt->execute();
        $result = $stmt->get_result();

        $productos = [];
        while ($producto = $result->fetch_object("Producto")) {
            $productos[] = $producto;
        }
        $stmt->close();
        $con->close();

        return $productos;
    }

    public static function getById($id)
    {
        $con = DataBase::connect();

        $stmt = $con->prepare("SELECT * FROM productos WHERE id = ? AND activo = 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $producto = $result->fetch_object("Producto");

        $stmt->close();
        $con->close();

        return $producto ?: null;
    }

    public static function getIngredientesTexto($producto_id)
    {
        $con = DataBase::connect();
        $sql = "SELECT GROUP_CONCAT(i.nombre SEPARATOR ' - ') AS ingredientes
            FROM producto_ingrediente pi
            INNER JOIN ingredientes i ON pi.ingrediente_id = i.id
            WHERE pi.producto_id = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();
        $con->close();

        return $row['ingredientes'] ?? '';
    }
}