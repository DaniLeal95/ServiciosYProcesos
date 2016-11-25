<?php

/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 17/11/16
 * Time: 10:19
 */
class Almacen implements JsonSerializable
{
    private $idproducto;
    private $tipo;
    private $cantidad;
    private $nombre;

    /**
     * Almacen constructor.
     * @param $idproducto
     * @param $tipo
     * @param $cantidad
     */
    public function __construct( $tipo, $cantidad,$nombre,$idproducto)
    {
        $this->idproducto = $idproducto;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->nombre=$nombre;
    }

    function jsonSerialize(){
        return array(
            'idproducto' => $this->idproducto,
            'tipo'=> $this->tipo,
            'cantidad' => $this->cantidad,
            'nombre'=> $this->nombre
        );
    }

    public function __sleep()
    {
        return array('idproducto','tipo','cantidad','nombre');
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
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
    public function getIdproducto()
    {
        return $this->idproducto;
    }

    /**
     * @param mixed $idproducto
     */
    public function setIdproducto($idproducto)
    {
        $this->idproducto = $idproducto;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param mixed $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }



}