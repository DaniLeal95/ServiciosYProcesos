<?php

/**
 * Created by PhpStorm.
 * User: Dani
 * Date: 02/02/2017
 * Time: 17:10
 */
class Usuario implements JsonSerializable
{

    private $id,$nombre,$passw;

    /**
     * Usuario constructor.
     * @param $id
     * @param $nombre
     * @param $passw
     */
    public function __construct($id, $nombre, $passw)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->passw = $passw;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getPassw()
    {
        return $this->passw;
    }

    /**
     * @param mixed $passw
     */
    public function setPassw($passw)
    {
        $this->passw = $passw;
    }

    public function jsonSerialize()
    {
        return array('id'=>$this->id,
            'nombre'=>$this->nombre,
            'passw'=>$this->passw);
    }

    public function __sleep()
    {
        return array('id','nombre','passw');
    }

}