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

        $almacen = new Almacen(0,
            $request->getBodyParameters()->tipo,
            $request->getBodyParameters()->cantidad,
            $request->getBodyParameters()->nombre);

        AlmacenHandlerModel::setAlmacen($almacen);
        if($request!=null){
            $code = '200';
        } else {
            $code = '400';
        }
        $response = new Response($code, null, $request->getAccept());
        $response->generate();

    }



}