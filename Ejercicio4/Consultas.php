 <?php

    $opcion = $_POST['consulta'];


  $conexion = new mysqli('localhost','root','','Prueba1');
  if($conexion->connect_error){
    trigger_error("Failed to connect to MySQL: ".$conexion->connect_error,E_USER_ERROR);
  }
    if($opcion=="insertar" && @$_POST['Insercion']) {
      $nombre = $_POST['nombre'];
      $edad = $_POST['edad'];
      $nacionalidad = $_POST['nacionalidad'];
    }

?>
<html>
  <body>
    <?php
          //OPCION CONSULTA
          if($opcion=="consulta"){
            
            echo "<h2> Consulta </h2>";
            $sql="Select * From tabla1";
            $result=$conexion->query($sql);
            if($result->num_rows > 0){
              
              while($row = $result-> fetch_assoc()){
                echo "nombre: ".$row["nombre"]. "- Edad: ".$row["edad"]."- Nacionalidad: ".$row["nacionalidad"]."<br/>";
                
              }
            } 
          }//Fin OPCION CONSULTA
    
          //OPCION INSERTAR
          else {
            echo "<h2> Insertar persona</h2>";
            echo "<form action='Consultas.php' method='POST'>
                  <p>Nombre</p><br/>
                  <input type='text' name='nombre'/><br/>
                  <p>Edad</p><br/>
                  <input type='number' name='edad'/><br/>
                  <p>Nacionalidad</p><br/>
                  <input type='text' name='nacionalidad'/>
                  <input type='submit' name='Insercion' value='Enviar'/>
                  </form>";

            // Preparar
            $stmt = $conexion->prepare("insert into tabla1 (nombre, edad, nacionalidad) VALUES (?, ?, ?)");
            $stmt->bind_param('sis', $nombre, $edad, $nacionalidad);

            if ($stmt->execute()) {

            // Mensaje de éxito en la inserción

                  echo "Se han creado las entradas exitosamente";
            }
                //Cerrar conexiones
                    $stmt->close();
                    $conexion->close();



                  //Anterior a la sentencias Preparadas sql

                  //$sql = "INSERT INTO 'Prueba1'.'tabla1' ('nombre','edad','nacionalidad') values (".$nombre.",".$edad.",".$nacionalidad.")";
                  /*if($conexion->query($sql)===TRUE){
                    echo "RECORD INSERTED SUCCESSFULLY";
                  }else{
                    echo "ERROR INSERTING RECORD".$conexion->error;
                  }*/
          }
    
    
    ?>
    
    
  </body>
</html>

 