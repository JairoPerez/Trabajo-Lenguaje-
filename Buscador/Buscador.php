<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCT</title>
        <?php
            include '..\auth.php';

    $host = 'localhost';
    $dbname = 'gestionfct';
    $user = 'root';
    $pass = '';

    $email = $_POST['email'] ?? null;
    $nia = $_POST['nia'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $nombre = $_POST['nombre'] ?? null;
    $cv = $_POST['cv'] ?? null;
    $password = $_POST['password'] ?? null;


    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_count = 'select count(*) from alumno';

        $stmt = $pdo->prepare($sql_count);
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    $count = ($stmt->fetch(PDO::FETCH_ASSOC));

    $pag_actual = $_POST['pag_actual'] ?? 1;
    $pag_anterior = $_POST['pag_anterior'] ?? null;
    $pag_siguiente = $_POST['pag_siguiente'] ?? null;
    $primera_pag = $_POST['primera_pag'] ?? null;
    $ultima_pag = $_POST['ultima_pag'] ?? null;
    $pag_totales = ceil($count['count(*)'] / 15);

    if ($ultima_pag) {
        $pag_actual = $pag_totales;
    }
    if ($primera_pag) {
        $pag_actual = 1;
    }
    if ($pag_siguiente && $pag_actual != $pag_totales) {
        $pag_actual++;
    }
    if ($pag_anterior && $pag_actual != 1) {
        $pag_actual--;
    }

    $calculo_pag = ($pag_actual * '15') - 15;

    $sql = "select * from alumno where true";
    $datos = [];

    if (!empty($email)) {
        $sql .= ' and email like :email';
        $datos[':email'] = $email;
    }

    if (!empty($nia)) {
        $sql .= ' and nia like :nia';
        $datos[':nia'] = $nia;
    }

            if(!empty($telefono)){
                $sql.=' and telefono like :telefono';
                $datos[':telefono']=$telefono;
            }

            if(!empty($nombre)){
                $sql.=' and nombre like :nombre';
                $datos[':nombre']=$nombre;
            }


    $sql .= ' limit ' . $calculo_pag . ', 15';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare($sql);
        $stmt->execute($datos);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }


    ?>

</head>

<body>

    <form id="myForm" action="Buscador.php" method="post">

        <h2>BUSCADOR</h2>

        <label for="email">Email</label>
        <input type="text" name="email" id="email">

        <label for="nia">Nia</label>
        <input type="text" name="nia" id="nia">

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono">

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre">


        <input type="submit" value="Enviar">
        <input type="reset" id="resetInput" value="Reset">

        <script>
            
            document.getElementById('resetInput').addEventListener('click', function() {
                // Recarga la página
                location.reload();
            });

            document.getElementById('myForm').addEventListener('reset', function() {
                // Envía el formulario después de un reset
                setTimeout(function() {
                    document.getElementById('myForm').submit();
                }, 0);
            });

        </script>

        <h2>TABLA ALUMNOS</h2>

            <table>
                <td>EMAIL</td>
                <td>NIA</td>
                <td>TELÉFONO</td>
                <td>NOMBRE</td>
                <td>OPCIONES</td>

        
                <?php
                    while($rows=$stmt->fetch(PDO::FETCH_ASSOC)){
                        echo  "<tr>";
                        echo   "<td>";
                        echo    $rows['email'];
                        echo    "</td>";
                        echo   "<td>";
                        echo    $rows['nia'];
                        echo    "</td>";
                        echo   "<td>";
                        echo    $rows['telefono'];
                        echo    "</td>";
                        echo   "<td>";
                        echo    $rows['nombre'];
                        echo    "</td>";
                        echo   "<td>";
                        echo    "<form action='crear_alumno.php' method='post'>";
                        echo    "<input type='submit' name='create' value='Crear'>";
                        echo    "<form action='modificar_alumno.php' method='post'>";
                        echo    "<input type='submit' name='modify' value='Modificar'>";
                        echo    "<form action='borrar_alumno.php' method='post'>";
                        echo    "<input type='submit' name='delete' value='Borrar'>";
                        echo    "</form>";
                        echo    "</td>";
                        echo    "</tr>";
                    }

                                 
                ?> 

        </table>

        <div>

            <input type="submit" name="primera_pag" value="<<">

            <input type="submit" name="pag_anterior" value="<">

            <input type="number" name="pag_actual" value="<?php echo $pag_actual ?>">

            <input type="submit" name="pag_siguiente" value=">">

            <input type="submit" name="ultima_pag" value=">>">

        </div>

    </form>


</body>

</html>