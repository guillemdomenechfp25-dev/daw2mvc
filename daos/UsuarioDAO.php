<?php
include_once("config/dataBase.php");
include_once("models/Usuario.php");

class UsuarioDAO
{
    public static function getByEmail($email)
    {
        $con = DataBase::connect();
        $stmt = $con->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $usuario = $result->fetch_object("Usuario");

        $stmt->close();
        $con->close();

        return $usuario ?: null;
    }

    public static function registrar(Usuario $usuario)
    {
        $con = DataBase::connect();
        $sql = "INSERT INTO usuarios (nombre, email, password, telefono, direccion, rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        $nombre = $usuario->getNombre();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword();
        $telefono = $usuario->getTelefono();
        $direccion = $usuario->getDireccion();
        $rol = $usuario->getRol() ?: 'usuario';

        $stmt->bind_param("ssssss", $nombre, $email, $password, $telefono, $direccion, $rol);
        $ok = $stmt->execute();
        $nuevoId = $con->insert_id;

        $stmt->close();
        $con->close();

        if ($ok) {
            $usuario->setId($nuevoId);
            return $usuario;
        }

        return null;
    }
}