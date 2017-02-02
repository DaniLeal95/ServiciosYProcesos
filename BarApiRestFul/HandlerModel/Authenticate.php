<?php

require_once "UsuarioHandlerModel.php";
require_once __DIR__.'/../model/Usuario.php';
/**
 * Created by PhpStorm.
 * User: Dani
 * Date: 02/02/2017
 * Time: 17:06
 */
class Authenticate
{

        function CompruebaAdmin($user, $pass)
    {
        $prueba = new UsuarioHandlerModel();
        $lista[] = $prueba->getUsuario(null);
        $existe = false;
        $listaPersonas = $lista[0];
        $id=0;
        $i = 0;
        
		do{
		
            if ($listaPersonas[$i]->getNombre() == $user && password_verify($pass, $listaPersonas[$i]->getPassw())) {
                
				$existe = true;
                $id=$listaPersonas[$i]->getId();
            }
            $i++;
    }while(!$existe && $i < count($listaPersonas));
        
			return $existe;
    }
}