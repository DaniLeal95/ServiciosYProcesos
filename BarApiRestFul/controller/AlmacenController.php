<?php

/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 17/11/16
 * Time: 10:26
 */

require_once "Controller.php";
class AlmacenController extends Controller
{
    public function manageGetVerb(Request $request)
    {

        $listaAlmacen = null;
        $id = null;
        $response = null;
        $code = null;

        //if the URI refers to a libro entity, instead of the libro collection
        if (isset($request->getUrlElements()[2])) {
            $id = $request->getUrlElements()[2];
        }


        $listaAlmacen = AlmacenHandlerModel::getAlmacen($id);

        if ($listaAlmacen != null) {
            $code = '200';

        } else {

            //We could send 404 in any case, but if we want more precission,
            //we can send 400 if the syntax of the entity was incorrect...
            if (AlmacenHandlerModel::isValid($id)) {
                $code = '404';
            } else {
                $code = '400';
            }

        }

        $response = new Response($code, null, $listaAlmacen, $request->getAccept());
        $response->generate();

    }

    public function managePostVerb(Request $request)
    {
        $almacen = null;

        for($i=0;$i<count($request->getBodyParameters());$i++) {
            $almacen = new Almacen(0,
                $request->getBodyParameters()[$i]->tipo,
                $request->getBodyParameters()[$i]->cantidad,
                $request->getBodyParameters()[$i]->nombre);

            AlmacenHandlerModel::setAlmacen($almacen);
        }
        if($request!=null){
            $code = '200';
        } else {
            $code = '400';
        }
        $response = new Response($code, null, $request->getAccept());
        $response->generate();

    }

    public function manageDeleteVerb(Request $request)
    {

        $id=-1;
        if (isset($request->getUrlElements()[2])) {
            $id = $request->getUrlElements()[2];
        }

        if($id!=-1){
            $res=AlmacenHandlerModel::deleteProduct($id);
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
    }

    public function managePutVerb(Request $request)
    {
        for ($i = 0; $i < count($request->getBodyParameters()); $i++) {
            if (isset($request->getBodyParameters()->idproducto)) {
                //Entonces es una actualizacion

                $almacen = new Almacen(
                    $request->getBodyParameters()[$i]->idproducto,
                    $request->getBodyParameters()[$i]->tipo,
                    $request->getBodyParameters()[$i]->cantidad,
                    $request->getBodyParameters()[$i]->nombre);
                //LLAMADA A ACTUALIZACION
                AlmacenHandlerModel::updateAlmacen($almacen);

            } else {
                //Entonces es una insercion
                $almacen = new Almacen(
                    0,
                    $request->getBodyParameters()[$i]->tipo,
                    $request->getBodyParameters()[$i]->cantidad,
                    $request->getBodyParameters()[$i]->nombre);
                AlmacenHandlerModel::setAlmacen($almacen);
            }
        }
        if($request!=null){
            $code = '200';
        } else {
            $code = '400';
        }
        $response = new Response($code, null, $request->getAccept());
        $response->generate();
    }

}