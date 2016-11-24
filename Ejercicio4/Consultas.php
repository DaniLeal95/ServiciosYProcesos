 <?php

    $opcion = $_POST['consulta'];


<<<<<<< HEAD
  $conexion = new mysqli('localhost','root','','gestionBar');
=======
  $conexion = new mysqli('localhost','root','','Prueba1');
>>>>>>> c67212a6cc4514ecb5a12843a4c8a8c1517d3b65
  if($conexion->connect_error){
    trigger_error("Failed to connect to MySQL: ".$conexion->connect_error,E_USER_ERROR);
  }
    if($opcion=="insertar" && @$_POST['Insercion']) {
<<<<<<< HEAD

=======
      $nombre = $_POST['nombre'];
      $edad = $_POST['edad'];
      $nacionalidad = $_POST['nacionalidad'];
>>>>>>> c67212a6cc4514ecb5a12843a4c8a8c1517d3b65
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
<<<<<<< HEAD

                echo "<table>
                        <tr>
                         
                        </tr>";

              while($row = $result-> fetch_assoc()){
                  echo "<tr>

                        </tr>";
              }
            }
            echo "<br/><a href='index.html'><input type='button' value='atras'></a>";
=======
              
              while($row = $result-> fetch_assoc()){
                echo "nombre: ".$row["nombre"]. "- Edad: ".$row["edad"]."- Nacionalidad: ".$row["nacionalidad"]."<br/>";
                
              }
            } 
>>>>>>> c67212a6cc4514ecb5a12843a4c8a8c1517d3b65
          }//Fin OPCION CONSULTA
    
          //OPCION INSERTAR
          else {
            echo "<h2> Insertar persona</h2>";
            echo "<form action='Consultas.php' method='POST'>
                  <p>Nombre</p><br/>
<<<<<<< HEAD
                  <input type='text' name='nombre' required='required'/><br/>
                  <p>Edad</p><br/>
                  <input type='number' name='edad' required='required'/><br/>
                  <p>Nacionalidad</p><br/>
                  <input type='text' name='nacionalidad' required='required'/>
                  
                  <input type='submit' name='Insercion' value='Enviar'/><br/><br/><br/>
                  
                  <a href='index.html'><input type='button' value='atras'></a>
                  </form>";

            // Preparar
            //$stmt = $conexion->prepare("insert into tabla1 (nombre, edad, nacionalidad) VALUES (?, ?, ?)");
            //$stmt->bind_param('sis', $nombre, $edad, $nacionalidad);
=======
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
>>>>>>> c67212a6cc4514ecb5a12843a4c8a8c1517d3b65

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

 