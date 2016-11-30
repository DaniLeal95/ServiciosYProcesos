<?php
Require_once "ConsAlmacenModel.php";
Require_once "Almacen.php";
/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 17/11/16
 * Time: 10:34
 */
class AlmacenHandlerModel
{

    public static function deleteProduct($id){
        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

        $select="SELECT TIPO FROM ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME." WHERE ".\ConstantesDB\ConsAlmacenModel::COD." = ?";
        $prep_select= $db_connection->prepare($select);
        $prep_select->bind_param("i",$id);
        $prep_select->execute();
        $prep_select->store_result();
        $prep_select->bind_result($tipo);
        while($prep_select->fetch()){}

        $prep_select->close();

        /*Comenzamos a eliminar*/
        switch ($tipo){
            case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA:
                $delete="Delete from ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA." where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";
                break;
            case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA:
                $delete="Delete from ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA." where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";
                break;
            case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO:
                $delete="Delete from ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO." where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";
                break;
        }

        $db=DatabaseModel::getInstance();
        $db_connection=$db->getConnection();
        $prep_delete=$db_connection->prepare($delete);

        $prep_delete->bind_param("i",$id);
        $res=$prep_delete->execute();
        $prep_delete->close();
        if($res){

            $db=DatabaseModel::getInstance();
            $db_connection=$db->getConnection();

            $delete="Delete from ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME." where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";
            $prep_delete=$db_connection->prepare($delete);

            $prep_delete->bind_param("i",$id);
            $res=$prep_delete->execute();
            $prep_delete->close();
        }
        return $res;

    }


    public static function setAlmacen($almacen){
        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

        $insert="INSERT INTO ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME." (".\ConstantesDB\ConsAlmacenModel::TIPO.",".\ConstantesDB\ConsAlmacenModel::CANTIDAD.")VALUES ('". $almacen->getTipo()."',".$almacen->getCantidad().")";

        $prep_insert=$db_connection->prepare($insert);

        $filasafectadas=$prep_insert->execute();

        $prep_insert->close();


        if($filasafectadas){

            //Cogemos ultimo id
            $db = DatabaseModel::getInstance();
            $db_connection = $db->getConnection();

            $select = "Select ".\ConstantesDB\ConsAlmacenModel::COD." from ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME. " order by ".\ConstantesDB\ConsAlmacenModel::COD." desc LIMIT 1";
            $prepQuery=$db_connection->prepare($select);

            $prepQuery->execute();
            $prepQuery->store_result();
            $prepQuery->bind_result($id);


            while($prepQuery->fetch()){

            }
            $prepQuery->close();
            //fin coger ultima id

            $db = DatabaseModel::getInstance();
            $db_connection = $db->getConnection();

            switch ($almacen->getTipo()){
                case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA:
                    $insert="Insert into ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA." (".\ConstantesDB\ConsAlmacenModel::COD.",".\ConstantesDB\ConsAlmacenModel::NOMBRE.") VALUES (?,'".$almacen->getNombre()."')";
                    break;
                case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA:
                    $insert="Insert into ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA." (".\ConstantesDB\ConsAlmacenModel::COD.",".\ConstantesDB\ConsAlmacenModel::NOMBRE.") VALUES (?,'".$almacen->getNombre()."')";
                    break;
                case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO:
                    $insert="Insert into ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO." (".\ConstantesDB\ConsAlmacenModel::COD.",".\ConstantesDB\ConsAlmacenModel::NOMBRE.") VALUES (?,'".$almacen->getNombre()."')";
                    break;
            }
            $prep_insert=$db_connection->prepare($insert);



            $prep_insert->bind_param('i',$id);

            $prep_insert->execute();

            $prep_insert->close();
        }
    }


    public static function updateAlmacen($almacen){
        $almacenexistente=self::getAlmacen($almacen->getIdproducto());

        if($almacenexistente->getTipo() != $almacen->getTipo()){

            //Si queremos modificar un producto y lo queremos cambiar de tipo
            //tenemos que quitarlo de la tabla 'tipo' anterior e insertarlo en la actual.
            self::deleteProduct($almacen->getIdproducto());

            self::setAlmacen($almacen);
        }
        else {
            $db = DatabaseModel::getInstance();
            $db_connection = $db->getConnection();

            $update = "UPDATE " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME . " SET ".\ConstantesDB\ConsAlmacenModel::CANTIDAD." = '" . $almacen->getCantidad() . "' WHERE " . \ConstantesDB\ConsAlmacenModel::COD . "= ?";

            $prep_update= $db_connection->prepare($update);
            $prep_update->bind_param("i",$almacen->getIdproducto());
            $prep_update->execute();
            $prep_update->close();


            //Ahora actualizamos la tabla del tipo si el nombre ha cambiado
            if($almacenexistente->getNombre()!=$almacen->getNombre()) {
                $db = DatabaseModel::getInstance();
                $db_connection = $db->getConnection();

                switch ($almacen->getTipo()) {
                    case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA:
                        $update = "UPDATE " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA . " SET nombre = '" . $almacen->getNombre() . "' WHERE " . \ConstantesDB\ConsAlmacenModel::COD . "= ?";
                        break;
                    case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA:
                        $update = "UPDATE " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA . " SET nombre=  '" . $almacen->getNombre() . "' WHERE " . \ConstantesDB\ConsAlmacenModel::COD . "= ?";
                        break;
                    case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO:
                        $update = "UPDATE " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO . " SET nombre=  '" . $almacen->getNombre() . "' WHERE " . \ConstantesDB\ConsAlmacenModel::COD . "= ?";
                        break;
                }


                $prep_update = $db_connection->prepare($update);
                $prep_update->bind_param("i", $almacen->getIdproducto());
                $prep_update->execute();
                $prep_update->close();
            }
        }
    }


    public static function getAlmacen($idproducto)
    {
        $listaAlmacen = null;

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
                . \ConstantesDB\ConsAlmacenModel::TIPO. ","
                . \ConstantesDB\ConsAlmacenModel::CANTIDAD . " FROM " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME;





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

            $prep_query->execute();$select = "Select top 1 ".\ConstantesDB\ConsAlmacenModel::COD." from ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME. " order by ".\ConstantesDB\ConsAlmacenModel::COD." desc";
            $prep_query->store_result();
            $listaAlmacen = array();

            //IMPORTANT: IN OUR SERVER, I COULD NOT USE EITHER GET_RESULT OR FETCH_OBJECT,
            // PHP VERSION WAS OK (5.4), AND MYSQLI INSTALLED.
            // PROBABLY THE PROBLEM IS THAT MYSQLND DRIVER IS NEEDED AND WAS NOT AVAILABLE IN THE SERVER:
            // http://stackoverflow.com/questions/10466530/mysqli-prepared-statement-unable-to-get-result

            $tipo = 0;
            $cantidad= 0;
            $prep_query->bind_result($idproducto, $tipo, $cantidad);

            while ($prep_query->fetch()) {
                $tipo = utf8_encode($tipo);
                $select="";

                switch($tipo){
                    case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA:

                        $select= "Select ".\ConstantesDB\ConsAlmacenModel::NOMBRE.
                            " From ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA.
                            " Where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";

                        break;

                    case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA:
                        $select = "Select ".\ConstantesDB\ConsAlmacenModel::NOMBRE.
                            " From ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA.
                            " Where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";


                    break;

                    case \ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO:
                        $select = "Select ".\ConstantesDB\ConsAlmacenModel::NOMBRE.
                            " From ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO.
                            " Where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";

                        break;
                }

                $prep_query2 = $db_connection->prepare($select);
                $prep_query2->bind_param('i', $idproducto);

                $prep_query2->execute();

                $prep_query2->bind_result($nombre);

                $prep_query2 -> store_result();
                while ($prep_query2->fetch()){

                }


                $almacen= new Almacen($idproducto, $tipo, $cantidad,$nombre);

                $listaAlmacen[] = $almacen;
            }



        }
        $db_connection->close();

        return $listaAlmacen;
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