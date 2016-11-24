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

            $prep_query->execute();
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


                switch($tipo){
                    case "bebida":

                        $selectNombrebebidaxid = "Select ".\ConstantesDB\ConsAlmacenModel::NOMBRE.
                            " From ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_BEBIDA.
                            " Where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";
                        //$db->closeConnection();

                        //$db = DatabaseModel::getInstance();
                        //$db_connection = $db->getConnection();

                        $prep_query2 = $db_connection->prepare($selectNombrebebidaxid);

                        //$prep_query2->bind_param('i', $idproducto);

                        $prep_query2->execute();

                        $res=$prep_query2 -> get_result();
                        while ($fila= $res->fetch_assoc()){
                            $nombre=$fila[\ConstantesDB\ConsAlmacenModel::NOMBRE];
                        }
                        echo $nombre;
                        $prep_query2->close();


                        //$prep_query2 = $db_connection->prepare($selectNombrebebidaxid);
                        /*$prep_query2 = $db_connection->query($selectNombrebebidaxid);
                        while($row = $prep_query2->fetch_assoc()) {
                            $nombre=$row["nombre"];
                        }*/
                        //$prep_query2->bind_param('i', $idproducto);


                        //$prep_query2->bind_result($nombre);
                        //$nombre=utf8_encode($nombre);

                        break;


//            $result = $prep_query->get_result();
//            for ($i = 0; $row = $result->fetch_object(LibroModel::class); $i++) {
//
//                $listaLibros[$i] = $row;
//            }
                    case "alimento":
                        $selectNombrealimentoxid = "Select ".\ConstantesDB\ConsAlmacenModel::NOMBRE.
                            " From ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_COMIDA.
                            " Where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";

                        $db->closeConnection();

                        $db = DatabaseModel::getInstance();
                        $db_connection = $db->getConnection();

                        $prep_query2 = $db_connection->prepare($selectNombrealimentoxid);

                        $prep_query2->bind_param('i', $idproducto);

                        $prep_query2->execute();
                        $prep_query2->store_result();
                        $prep_query2 -> get_result($nombre);
                        $prep_query2 -> close();
                        $nombre=utf8_encode($nombre);
                        break;
                    case "suministro":
                        $selectNombresuministroxid = "Select ".\ConstantesDB\ConsAlmacenModel::NOMBRE.
                            " From ".\ConstantesDB\ConsAlmacenModel::TABLE_NAME_SUMINISTRO.
                            " Where ".\ConstantesDB\ConsAlmacenModel::COD." = ?";

                        $db->closeConnection();

                        $db = DatabaseModel::getInstance();
                        $db_connection = $db->getConnection();

                        $prep_query2 = $db_connection->prepare($selectNombresuministroxid);

                        $prep_query2->bind_param('i', $idproducto);

                        $prep_query2->execute();
                        $prep_query2->store_result();
                        $prep_query2-> bind_result($nombre);
                        $prep_query2 -> close();
                        $nombre=utf8_encode($nombre);
                        break;
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