<?php
require_once __DIR__.'/../model/ConsAlmacenModel.php';
require_once __DIR__.'/../model/Usuario.php';
require_once __DIR__.'/../model/DatabaseModel.php';
/**
 * Created by PhpStorm.
 * User: Dani
 * Date: 02/02/2017
 * Time: 17:15
 */
class UsuarioHandlerModel
{

    public static function addUsuario(Usuario $usuario)
    {
        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

        $usuario->setPassw(password_hash($usuario->getPassw(),PASSWORD_BCRYPT));
		
		
        $insert = "INSERT INTO " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_USUARIOS . " (" . \ConstantesDB\ConsAlmacenModel::NOMBREUSUARIO . "," . \ConstantesDB\ConsAlmacenModel::PASSWUSUARIO . ")VALUES ('" . $usuario->getNombre() . "','" . $usuario->getPassw() ."')";

        $prep_insert = $db_connection->prepare($insert);

        $filasafectadas = $prep_insert->execute();

        $prep_insert->close();

        return $filasafectadas;
    }


    public static function updateUsuario($usuario)
    {


        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();

        $update = "UPDATE " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_USUARIOS . " SET " . \ConstantesDB\ConsAlmacenModel::NOMBREUSUARIO . " = '" . $usuario->getNombre() . "'," . \ConstantesDB\ConsAlmacenModel::PASSWUSUARIO . " = " . $usuario->getPassw() . " WHERE " . \ConstantesDB\ConsAlmacenModel::IDUSUARIO . "= ?";

        $prep_update = $db_connection->prepare($update);
        $prep_update->bind_param("i", $usuario->getId());
        $actualizacion = $prep_update->execute();
        $prep_update->close();

        return $actualizacion;
    }


    public static function getUsuario($idusuario)
    {
        $listaUsuarios = null;

        $db = DatabaseModel::getInstance();
        $db_connection = $db->getConnection();


        //IMPORTANT: we have to be very careful about automatic data type conversions in MySQL.
        //For example, if we have a column named "cod", whose type is int, and execute this query:
        //SELECT * FROM table WHERE cod = "3yrtdf"
        //it will be converted into:
        //SELECT * FROM table WHERE cod = 3
        //That's the reason why I decided to create isValid method,
        //I had problems when the URI was like libro/2jfdsyfsd

        $valid = self::isValid($idusuario);

        //If the $id is valid or the client asks for the collection ($id is null)
        if ($valid === true || $idusuario == null) {
            $query = "SELECT " . \ConstantesDB\ConsAlmacenModel::IDUSUARIO . ","
                . \ConstantesDB\ConsAlmacenModel::NOMBREUSUARIO . ","
                . \ConstantesDB\ConsAlmacenModel::PASSWUSUARIO .
                " FROM " . \ConstantesDB\ConsAlmacenModel::TABLE_NAME_USUARIOS;


            if ($idusuario != null) {
                $query = $query . " WHERE " . \ConstantesDB\ConsAlmacenModel::IDUSUARIO . " = ?";
            }

            $prep_query = $db_connection->prepare($query);

            //IMPORTANT: If we do not want to expose our primary keys in the URIS,
            //we can use a function to transform them.
            //For example, we can use hash_hmac:
            //http://php.net/manual/es/function.hash-hmac.php
            //In this example we expose primary keys considering pedagogical reasons

            if ($idusuario != null) {
                $prep_query->bind_param('i', $idusuario);
            }

            $prep_query->execute();

            $prep_query->store_result();
            $listaUsuarios = array();

            //IMPORTANT: IN OUR SERVER, I COULD NOT USE EITHER GET_RESULT OR FETCH_OBJECT,
            // PHP VERSION WAS OK (5.4), AND MYSQLI INSTALLED.
            // PROBABLY THE PROBLEM IS THAT MYSQLND DRIVER IS NEEDED AND WAS NOT AVAILABLE IN THE SERVER:
            // http://stackoverflow.com/questions/10466530/mysqli-prepared-statement-unable-to-get-result



            $prep_query->bind_result($idusuario, $nombre, $passw );

            while ($prep_query->fetch()) {

                $nombre = utf8_encode($nombre);

                $usuario = new Usuario($idusuario, $nombre, $passw);

                $listaUsuarios[] = $usuario;
            }


        }
        $db_connection->close();

        return $listaUsuarios;
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