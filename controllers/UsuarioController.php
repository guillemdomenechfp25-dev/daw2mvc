<?php
include_once("daos/UsuarioDAO.php");

class UsuarioController
{
    public function login()
    {
        $view = "views/login.php";
        include_once 'views/main.php';
    }

    public function registro()
    {
        $view = "views/registro.php";
        include_once 'views/main.php';
    }

    public function procesarLogin()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $redirect = $_POST['redirect'] ?? '';

        $usuario = UsuarioDAO::getByEmail($email);

        if (!$usuario || !password_verify($password, $usuario->getPassword())) {
            $error = "Email o contraseña incorrectos.";
            $view = "views/login.php";
            include_once 'views/main.php';
            return;
        }

        $this->guardarSesionYRedirigir($usuario, $redirect);
    }

    public function procesarRegistro()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $password = $_POST['password'] ?? '';
        $redirect = $_POST['redirect'] ?? '';

        if ($nombre === '' || $email === '' || $telefono === '' || $direccion === '' || $password === '') {
            $error = "Por favor, rellena todos los campos.";
            $view = "views/registro.php";
            include_once 'views/main.php';
            return;
        }

        if (UsuarioDAO::getByEmail($email)) {
            $error = "Ya existe una cuenta registrada con ese email.";
            $view = "views/registro.php";
            include_once 'views/main.php';
            return;
        }

        $nuevoUsuario = new Usuario();
        $nuevoUsuario->setNombre($nombre)
            ->setEmail($email)
            ->setPassword(password_hash($password, PASSWORD_BCRYPT))
            ->setTelefono($telefono)
            ->setDireccion($direccion)
            ->setRol('usuario');

        $creado = UsuarioDAO::registrar($nuevoUsuario);

        if (!$creado) {
            $error = "No se pudo crear la cuenta. Inténtalo de nuevo.";
            $view = "views/registro.php";
            include_once 'views/main.php';
            return;
        }

        $this->guardarSesionYRedirigir($creado, $redirect);
    }

    public function logout()
    {
        unset($_SESSION['usuario']);
        header("Location: index.php?controller=home&action=index");
        exit;
    }

    private function guardarSesionYRedirigir(Usuario $usuario, $redirect = '')
    {
        $_SESSION['usuario'] = [
            'id' => $usuario->getId(),
            'nombre' => $usuario->getNombre(),
            'email' => $usuario->getEmail(),
            'telefono' => $usuario->getTelefono(),
            'direccion' => $usuario->getDireccion(),
            'rol' => $usuario->getRol()
        ];

        unset($_SESSION['redirect_after_login']);

        if ($redirect === 'entrega') {
            header("Location: index.php?controller=carrito&action=entrega");
        } else {
            header("Location: index.php?controller=carta&action=index");
        }
        exit;
    }

    public function perfil()
    {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=usuario&action=login");
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $view = "views/perfil.php";
        include_once 'views/main.php';
    }

}