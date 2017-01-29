<?php

/**
 * Created by PhpStorm.
 * User: Dani
 * Date: 29/01/2017
 * Time: 22:56
 */
class Mesa implements JsonSerializable
{

    private $nummesa;
    private $codigo;
    private $disponibilidad;

    /**
     * Mesa constructor.
     * @param $nummesa
     * @param $codigo
     * @param $disponibilidad
     */
    public function __construct($nummesa, $codigo, $disponibilidad)
    {
        $this->nummesa = $nummesa;
        $this->codigo = $codigo;
        $this->disponibilidad = $disponibilidad;
    }

    /**
     * @return mixed
     */
    public function getNummesa()
    {
        return $this->nummesa;
    }

    /**
     * @param mixed $nummesa
     */
    public function setNummesa($nummesa)
    {
        $this->nummesa = $nummesa;
    }

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getDisponibilidad()
    {
        return $this->disponibilidad;
    }

    /**
     * @param mixed $disponibilidad
     */
    public function setDisponibilidad($disponibilidad)
    {
        $this->disponibilidad = $disponibilidad;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array('nummesa'=>$this->nummesa,
                    'codigo'=>$this->codigo,
                    'disponibilidad'=>$this->disponibilidad);
    }

    public function __sleep()
    {
        return array('nummesa','codigo','disponibilidad');
    }

}