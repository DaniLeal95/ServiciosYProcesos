<?php
require_once __DIR__.'/../HandlerModel/UsuarioHandlerModel.php';
require_once __DIR__.'/../model/Usuario.php';
/**
 * Created by PhpStorm.
 * User: Dani
 * Date: 02/02/2017
 * Time: 17:13
 */
class UsuarioController
{
    public function manageGetVerb(Request $request)
    {

        $listaUsuarios = null;
        $id = null;
        $response = null;
        $code = null;


        if (isset($request->getUrlElements()[2])) {
            $id = $request->getUrlElements()[2];
        }


        $listaUsuario = UsuarioHandlerModel::getUsuario($id);

        if ($listaUsuario != null) {
            $code = '200';

        } else {

            //We could send 404 in any case, but if we want more precission,
            //we can send 400 if the syntax of the entity was incorrect...
            if (UsuarioHandlerModel::isValid($id)) {
                $code = '404';
            } else {
                $code = '400';
            }

        }

        $response = new Response($code, null, $listaUsuario, $request->getAccept());
        $response->generate();

    }

    public function managePostVerb(Request $request)
    {
        $usuario = null;
        $filasafectadas = null;

 
        for ($i = 0; $i < count($request->getBodyParameters()); $i++) {
           
			$usuario = new Usuario(0,
                $request->getBodyParameters()[$i]->nombre,
                $request->getBodyParameters()[$i]->passw);
			
            $filasafectadas = UsuarioHandlerModel::addUsuario($usuario);

        }
        if ($request != null && $filasafectadas) {
            $code = '200';


        } else {
            $code = '400';

        }
        $response = new Response($code, null, $request->getAccept());
        $response->generate();

    }

    /*
        public function manageDeleteVerb(Request $request)
        {

            $id=-1;
            if (isset($request->getUrlElements()[2])) {
                $id = $request->getUrlElements()[2];
            }

            if($id!=-1){
                $res=ProductoHandlerModel::deleteProduct($id);
            }

            if($res!=-1) {
                if ($request != null) {
                    $code = '200';
                }
                else{
                    $code = '400';
                }
            }
            $response = new Response($code,null,$request->getAccept());
            $response->generate();
        }*/

    public function managePutVerb(Request $request)
    {
        $usuario = new Usuario(
            $request->getUrlElements()[2],
            $request->getBodyParameters()->nombre,
            $request->getBodyParameters()->passw
        );
        //LLAMADA A ACTUALIZACION
        $actualizacion = UsuarioHandlerModel::updateUsuario($usuario);


        if ($request != null && $actualizacion) {
            $code = '200';
        } else {
            $code = '400';
        }
        $response = new Response($code, null, $request->getAccept());
        $response->generate();
    }


}