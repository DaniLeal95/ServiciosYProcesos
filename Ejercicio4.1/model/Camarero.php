<?php

/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 17/11/16
 * Time: 10:14
 */
class Camarero
{
    private $idcamarero;
    private $nombre;
    private $apellidos;

    public function __construct($idcamarero,$nombre,$apellidos)
    {
        $this->idcamarero=$idcamarero;
        $this->nombre=$nombre;
        $this->apellidos=$apellidos;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return mixed
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @return mixed
     */
    public function getIdcamarero()
    {
        return $this->idcamarero;
    }

    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @param mixed $idcamarero
     */
    public function setIdcamarero($idcamarero)
    {
        $this->idcamarero = $idcamarero;
    }
}