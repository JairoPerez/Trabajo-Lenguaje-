<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
         table, td, tr{
            border: 1px solid;
        }
    </style>
    <?php
        $host='localhost';
        $dbname='universidad';
        $user='root';
        $pass='';

        $dni=$_POST['dni'] ?? null;
        $nombre=$_POST['nombre'] ?? null;
        $apellido_1=$_POST['apellido1'] ?? null;
        $apellido_2=$_POST['apellido2'] ?? null;
        

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $sql_count = 'select count(*) from alumno';
  
            $stmt=$pdo->prepare($sql_count);
            $stmt->execute();
          }
          catch(PDOException $e) {
              echo $e->getMessage();
          }

          $count=($stmt->fetch(PDO::FETCH_ASSOC));


        $pag_actual =$_POST['pag_actual'] ?? 1;
        $calculo_pag =$_POST['calculo_pag'] ?? null;
        $pag_anterior =$_POST['pag_anterior'] ?? null;
        $pag_siguiente =$_POST['pag_siguiente'] ?? null;
        $primera_pag = $_POST['primera_pag'] ?? null;
        $ultima_pag = $_POST['ultima_pag'] ?? null;
        $registros = $_POST['registros'] ?? null;
        $registros_mostrados = $_POST['registros_mostrados'] ?? 15;
        $limit = $_POST['limit'] ?? null;


        $pag_totales = ceil($count['count(*)']/$registros_mostrados);
        print_r($count);
        
     
        if($ultima_pag){
            echo "Ha pulsado ultima pagina";
        
        }if($primera_pag){
            echo "Ha pulsado la primera pagina";
        
        }if($pag_actual){
            echo "Ha pulsado la pagina actual";
        
        }if($pag_siguiente){
            echo "Ha pulsado la pagina siguiente";
        
        }if($pag_anterior){
            echo "Ha pulsado la pagina anterior";
        }
        

        $sql="select * from alumno where true ";
        $datos=[];

        if(!empty($dni)){
            $sql.=' and dni like :dni';
            $datos[':dni']=$dni;
        }

        if(!empty($nombre)){
            $sql.=' and nombre like :nombre';
            $datos[':nombre']=$nombre;
        }

        if(!empty($apellido_1)){
            $sql.=' and apellido_1 like :apellido1';
            $datos[':apellido1']=$apellido_1;
        }

        if(!empty($apellido_2)){
            $sql.=' and apellido_2 like :apellido2';
            $datos[':apellido2']=$apellido_2;
        }

        $sql.='limit 0, 10';

        try {
          $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
          $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

          $stmt=$pdo->prepare($sql);
          $stmt->execute($datos);
          //echo $sql; NOS ENSEÑA NUESTRA SQL
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }

    ?>

</head>
<body>

    <form action="Trabajo.php" method="post">

        <label for="dni">DNI</label>
        <input type="text" name="dni" id="dni">

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre">

        <label for="apellido_1">Primer apellido</label>
        <input type="text" name="apellido_1" id="apellido_1"
        >
        <label for="apellido_2">Segundo apellido</label>
        <input type="text" name="apellido_2" id="apelido_2">

        <input type="submit" value="Enviar">
        <input type="reset" value="Reset">


        <h2>TABLA ALUMNOS</h2>
        <table>
        <td>DNI:</td>
        <td>NOMBRE:</td>
        <td>PRIMER APELLIDO:</td>
        <td>SEGUNDO APELLIDO:</td>
        
        <?php
            while($rows=$stmt->fetch(PDO::FETCH_ASSOC)){
                echo  "<tr>";
                echo   "<td>";
                echo    $rows['DNI'];
                echo    "</td>";
                echo   "<td>";
                echo    $rows['NOMBRE'];
                echo    "</td>";
                echo   "<td>";
                echo    $rows['APELLIDO_1'];
                echo    "</td>";
                echo   "<td>";
                echo    $rows['APELLIDO_2'];
                echo    "</td>";
                echo    "</tr>";
            }
        ?> 

        </table>

        <div>
        <input type="submit" name="primera_pag" value="<<">
            
        <input type="submit" name="pag_anterior" value="<">

        <input type="submit" name="pag_actual" value="<--Página actual-->">

        <input type="submit" name="pag_siguiente" value=">">

        <input type="submit" name="ultima_pag" value=">>">

        </div>

    </ >
        
    
</body>
</html>