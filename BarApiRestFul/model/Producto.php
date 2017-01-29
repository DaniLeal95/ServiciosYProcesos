<?php

/**
 * Created by PhpStorm.
 * User: Dani
 * Date: 29/01/2017
 * Time: 22:40
 */
class Producto implements JsonSerializable
{

    private $idproducto;
    private $idcategoria;
    private $nombre;
    private $precio;


    public function __construct( $idproducto,$idcategoria,$nombre,$precio)
    {
        $this->idproducto = $idproducto;
        $this->idcategoria = $idcategoria;
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return array(
            'idproducto' => $this->idproducto,
            'idcategoria'=> $this->idcategoria,
            'nombre'=> $this->nombre,
            'precio'=> $this->precio
        );

    }


    public function __sleep()
    {
      return array('idproducto',
          'idcategoria',
          'nombre',
          'precio');
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
    public function getIdcategoria()
    {
        return $this->idcategoria;
    }

    /**
     * @param mixed $idcategoria
     */
    public function setIdcategoria($idcategoria)
    {
        $this->idcategoria = $idcategoria;
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
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param mixed $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }


}