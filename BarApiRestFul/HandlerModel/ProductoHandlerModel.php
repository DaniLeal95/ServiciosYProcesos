<?php

require_once __DIR__.'/../model/ConsAlmacenModel.php';
require_once __DIR__.'/../model/Producto.php';
require_once __DIR__.'/../model/DatabaseModel.php';
/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 2/02/17
 * Time: 8:33
 */
class ProductoHandlerModel
{


   /* public static function deleteProduct($id){
        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

            $delete="Delete from ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_PRODUCTOS." where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";
            $prep_delete=$db_connection->prepare($delete);

            $prep_delete->bind_param("i",$id);
            $res=$prep_delete->execute();
            $prep_delete->close();

        return $res;

    }*/


    public static function addProducto($producto){
        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

        $insert="INSERT INTO ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_PRODUCTOS." (".\ConstantesDB\ConsAlmacenModel::NOMBRE.",".\ConstantesDB\ConsAlmacenModel::PRECIO.",".\ConstantesDB\ConsAlmacenModel::TIPO.")VALUES ('". $producto->getNombre()."',".$producto->getPrecio().",".$producto->getIdcategoria().")";

		
        $prep_insert=$db_connection->prepare($insert);

        $filasafectadas=$prep_insert->execute();
		
        $prep_insert->close();

        return $filasafectadas;
    }


    public static function updateProducto($producto){




            $db = DatabaseModel::getInstance();
            $db_connection = $db->getConnection();

            $update = "UPDATE " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_PRODUCTOS . " SET ".\ConstantesDB\ConsAlmacenModel::NOMBRE." = '" . $producto->getNombre() . "',".\ConstantesDB\ConsAlmacenModel::PRECIO." = ".$producto->getPrecio().", ".\ConstantesDB\ConsAlmacenModel::TIPO." = ".$producto->getIdcategoria()." WHERE " . \ConstantesDB\ConsAlmacenModel::COD . "= ?";

            $prep_update= $db_connection->prepare($update);
            $prep_update->bind_param("i",$producto->getIdproducto());
            $actualizacion=$prep_update->execute();
            $prep_update->close();

        return $actualizacion;
    }


    public static function getProducto($idproducto)
    {
        $listaProducto = null;

        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();



        //IMPORTANT: we have to be very careful about automatic data type conversions in MySQL.
        //For example, if we have a column named "cod", whose type is int, and execute this query:
        //SELECT * FROM table WHERE cod = "3yrtdf"
        //it will be converted into:
        //SELECT * FROM table WHERE cod = 3
        //That's the reason why I decided to create isValid method,
        //I had problems when the URI was like libro/2jfdsyfsd

        $valid = self::isValid($idproducto);

        //If the $id is valid or the client asks for the collection ($id is null)
        if ($valid === true || $idproducto == null) {
            $query = "SELECT " . \ConstantesDB\ConsAlmacenModel::COD . ","
                . \ConstantesDB\ConsAlmacenModel::NOMBRE. ","
                . \ConstantesDB\ConsAlmacenModel::PRECIO .","
                . \ConstantesDB\ConsAlmacenModel::TIPO .
            " FROM " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_PRODUCTOS;





            if ($idproducto != null) {
                $query = $query . " WHERE " . \ConstantesDB\ConsAlmacenModel::COD . " = ?";
            }

            $prep_query = $db_connection->prepare($query);

            //IMPORTANT: If we do not want to expose our primary keys in the URIS,
            //we can use a function to transform them.
            //For example, we can use hash_hmac:
            //http://php.net/manual/es/function.hash-hmac.php
            //In this example we expose primary keys considering pedagogical reasons

            if ($idproducto != null) {
                $prep_query->bind_param('i', $idproducto);
            }

            $prep_query->execute();

            $prep_query->store_result();
            $listaProducto = array();

            //IMPORTANT: IN OUR SERVER, I COULD NOT USE EITHER GET_RESULT OR FETCH_OBJECT,
            // PHP VERSION WAS OK (5.4), AND MYSQLI INSTALLED.
            // PROBABLY THE PROBLEM IS THAT MYSQLND DRIVER IS NEEDED AND WAS NOT AVAILABLE IN THE SERVER:
            // http://stackoverflow.com/questions/10466530/mysqli-prepared-statement-unable-to-get-result

            $idcategoria=0;

            $prep_query->bind_result($idproducto, $nombre,$precio, $idcategoria);

            while ($prep_query->fetch()) {

                $nombre = utf8_encode($nombre);

                $producto= new Producto($idproducto, $nombre, $precio,$idcategoria);

                $listaProducto[] = $producto;
            }



        }
        $db_connection->close();

        return $listaProducto;
    }

    //returns true if $id is a valid id for a book
    //In this case, it will be valid if it only contains
    //numeric characters, even if this $id does not exist in
    // the table of books
    public static function isValid($id)
    {
        $res = false;

        if (ctype_digit($id)) {
            $res = true;
        }
        return $res;
    }

}