<?php

/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 17/11/16
 * Time: 10:26
 */

require_once "Controller.php";
require_once __DIR__.'/../HandlerModel/ProductoHandlerModel.php';

class ProductoController extends Controller
{
    public function manageGetVerb(Request $request)
    {

        $listaProducto = null;
        $id = null;
        $response = null;
        $code = null;


        if (isset($request->getUrlElements()[2])) {
            $id = $request->getUrlElements()[2];
        }


        $listaProducto = ProductoHandlerModel::getProducto($id);

        if ($listaProducto != null) {
            $code = '200';

        } else {

            //We could send 404 in any case, but if we want more precission,
            //we can send 400 if the syntax of the entity was incorrect...
            if (ProductoHandlerModel::isValid($id)) {
                $code = '404';
            } else {
                $code = '400';
            }

        }

        $response = new Response($code, null, $listaProducto, $request->getAccept());
        $response->generate();

    }

    public function managePostVerb(Request $request)
    {
        $producto = null;
        $filasafectadas=null;


        for($i=0;$i<count($request->getBodyParameters());$i++) {
            $producto = new Producto(0,
                $request->getBodyParameters()[$i]->idcategoria,
                $request->getBodyParameters()[$i]->nombre,
                $request->getBodyParameters()[$i]->precio
                );

            $filasafectadas=ProductoHandlerModel::addProducto($producto);

        }
        if($request!=null && $filasafectadas){
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


        $producto = new Producto(
            $request->getUrlElements()[2],
            $request->getBodyParameters()->idcategoria,
            $request->getBodyParameters()->nombre,
            $request->getBodyParameters()->precio
            );
                //LLAMADA A ACTUALIZACION
        $actualizacion=ProductoHandlerModel::updateProducto($producto);



        if($request!=null && $actualizacion){
            $code = '200';
        } else {
            $code = '400';
        }
        $response = new Response($code, null, $request->getAccept());
        $response->generate();
    }

}