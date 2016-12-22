<?php
/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 22/12/16
 * Time: 10:36
 */
$conexion = new mysqli('localhost','pepito','12341234','pruebahash');
if($conexion->connect_error){
    trigger_error("Failed to connect to MySQL: ".$conexion->connect_error,E_USER_ERROR);
}
$opcion=$_POST['consulta'];
$usuario=$_POST['user'];
$contrasena=$_POST['password'];
?>
<html>
  <body>
  <?php
  $contrasenaencriptada = password_hash($contrasena, PASSWORD_DEFAULT) ;
    if($opcion == "registrar"){


        $sql = "INSERT INTO Usuarios (Usuario,Contrasena) values ('".$usuario."','".$contrasenaencriptada."')";
        if($conexion->query($sql)===TRUE){
            echo "RECORD INSERTED SUCCESSFULLY";
        }else{
            echo "ERROR INSERTING RECORD".$conexion->error;
        }

    }
    else{
        //Falta implementar

    }
  ?>
  </body>
</html>