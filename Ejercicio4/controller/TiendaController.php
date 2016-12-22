<?php

/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 1/12/16
 * Time: 10:51
 */
require_once "Controller.php";
class TiendaController extends Controller
{

    public function managePutVerb(Request $request)
    {
        //Si hay codigo

        if(isset($request->getBodyParameters()->cod)){

            $tienda = new Tienda(
                $request->getBodyParameters()->cod,
                $request->getBodyParameters()->nombre,
                $request->getBodyParameters()->descripcion,
                $request->getBodyParameters()->precio
            );


                if (!ctype_digit($tienda->getCod())) {
                    //Si el producto existe en la bbdd
                    //Entonces es una actualizacion
                    if (TiendaHandlerModel::existeProducto($tienda->getCod())) {
                        //Esta condicion nos indica que los parametros sean correctos
                        if ((is_float($tienda->getPrecio())
                            || ctype_digit($tienda->getPrecio())
                            &&
                            is_string(($tienda->getDescripcion()))
                            &&
                            is_string(($tienda->getNombre()))
                            )
                        ) {

                            //LLAMADA A ACTUALIZACION
                            TiendaHandlerModel::actualizarProducto($tienda);
                        } else {
                            $code = '409';
                        }
                    } else {
                        $code = '409';
                    }
                }

                //Si no existe en la bbdd es un error porque el codigo es autoincrementado
                //y no lo podemos meter a mano
                else {
                    $code = '409';
                }



            } else {
                //Entonces es una insercion
                $tienda = new Tienda(
                    0,
                    $request->getBodyParameters()->nombre,
                    $request->getBodyParameters()->descripcion,
                    $request->getBodyParameters()->precio);

                //Esta condicion nos indica que los parametros sean correctos
                if ((is_float($tienda->getPrecio())
                    || ctype_digit($tienda->getPrecio())
                    &&
                    is_string(($tienda->getDescripcion()))
                    &&
                    is_string(($tienda->getNombre()))
                )
                ) {

                    //LLAMADA A Insercion
                    TiendaHandlerModel::insertaProducto($tienda);
                } else {
                    $code = '409';
                }



        }
        //Si no hay errores de datos
        if($code==null) {
            if ($request != null) {
                $code = '200';
            } else {
                $code = '400';
            }
        }

            $response = new Response($code, null, $request->getAccept());
            $response->generate();

    }

}