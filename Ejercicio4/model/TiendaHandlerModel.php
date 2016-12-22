<?php

/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 1/12/16
 * Time: 10:43
 */
class TiendaHandlerModel
{

    /*
     * Interfaz
     *  breve comentario:
     *      Este metodo actualizara un producto existente en la bbdd, con los atributos del producto
     *          recibidos en los parametros.
     *
     * Precondiciones:
     *      los datos deben ser validos
     * Entradas:
     *      un producto
     * salidas:
     *      entero con las filas afectadas en la bbdd
     * Postcondiciones
     *      lo devolveremos asociado al nombre.
     * */
    public static function actualizarProducto($producto){

        $filas=0;
        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

        $update="UPDATE productos SET nombre= '".$producto->getNombre()."',descripcion = '".$producto->getDescripcion()."',precio = ".$producto->getPrecio()." WHERE cod = ".$producto->getCod();

        $prep_insert=$db_connection->prepare($update);

        $filasafectadas=$prep_insert->execute();

        $prep_insert->close();
        if($filasafectadas){
            $filas= 1;
        }
        return $filas;

    }

    /*
     * Interfaz insertarProducto
     *
     * Breve Comentario:
     *      El metodo insertara un producto en la bbdd con los parametros
     *          del producto recibido.
     * Precondiciones:
     *      El producto debe ser valido
     * Entrada:
     *      Un producto
     * Salida:
     *      Un entero con las filas afectadas en la bbdd
     * PostCondiciones:
     *      lo devolveremos asociado al nombre
     * */

    public static function insertaProducto($producto){
        $filas=0;
        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

        $insert="INSERT INTO productos (nombre,descripcion,precio) Values ('".$producto->getNombre()."', '".$producto->getDescripcion()."',".$producto->getPrecio().")";

        $prep_insert=$db_connection->prepare($insert);

        $filasafectadas=$prep_insert->execute();

        $prep_insert->close();
        if($filasafectadas){
           $filas= 1;
        }
        return $filas;
    }





    /*
 * Interfaz existeProducto
 *
 * Breve Comentario:
 *      el metodo mirara en la bbdd si existe el codigo que nos pasan por parametros
 * Precondiciones:
 *      nada
 * Entrada:
 *      Un entero
 * Salida:
 *      Un boolean si existe o no el producto en la bbdd
 * PostCondiciones:
 *      lo devolveremos asociado al nombre
 * */
    public static function  existeProducto($cod){
        $existe=false;
        $prod=null;
        if(self::isValid($cod)){

            $db = DatabaseModel::getInstance();
            $db_connection = $db->getConnection();

            $select="SELECT * from productos Where cod = ".$cod;

            $prep_select=$db_connection->prepare($select);
            $prep_select->execute();
            $prep_select->store_result();
            $prep_select->bind_result($prod);
            while($prep_select->fetch()){
                $existe=true;
            }


        }
        return $existe;
    }

    //returns true if $id is a valid id for a book
    //In this case, it will be valid if it only contains
    //numeric characters, even if this $id does not exist in
    // the table of books
    public static function isValid($id)
    {
        $res = false;

        if (!ctype_digit($id)) {
            $res = true;
        }
        return $res;
    }
}