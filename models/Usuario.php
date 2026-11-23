<?php

class Usuario
{
    protected $id;
    protected $nombre;
    protected $email;
    protected $password;
    protected $telefono;
    protected $direccion;
    protected $rol;

    // --- GETTERS ---
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getRol()
    {
        return $this->rol;
    }

    // --- SETTERS ---
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
        return $this;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
        return $this;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
        return $this;
    }
}