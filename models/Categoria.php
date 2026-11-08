<?php
class Categoria
{
    protected $id;
    protected $nombre;
    protected $descripcion;
    protected $activa;

    /**
     * fetch_object() rellena las propiedades ANTES de llamar al constructor.
     * Se comprueba !== null para no machacar los datos inyectados por MySQLi.
     */
    public function __construct($id = null, $nombre = null, $descripcion = null, $activa = null)
    {
        if ($id !== null) {
            $this->id = $id;
        }
        if ($nombre !== null) {
            $this->nombre = $nombre;
        }
        if ($descripcion !== null) {
            $this->descripcion = $descripcion;
        }
        if ($activa !== null) {
            $this->activa = $activa;
        }
    }

    // --- GETTERS ---
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getActiva()
    {
        return $this->activa;
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
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    public function setActiva($activa)
    {
        $this->activa = $activa;
        return $this;
    }
}