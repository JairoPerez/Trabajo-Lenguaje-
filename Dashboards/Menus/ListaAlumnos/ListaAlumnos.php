<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCT</title>
    <?php
    include '..\..\..\auth.php';
    // Configuración de la base de datos
    $host = 'localhost';
    $dbname = 'gestionfct';
    $user = 'root';
    $pass = '';

    // Conexión a la base de datos
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql_count = 'SELECT COUNT(*) FROM alumno';
        $stmt = $pdo->prepare($sql_count);
        $stmt->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    // Paginación
    $count = ($stmt->fetch(PDO::FETCH_ASSOC));
    $pag_actual = $_POST['pag_actual'] ?? 1;
    $pag_totales = ceil($count['COUNT(*)'] / 15);

    if (isset($_POST['ultima_pag'])) {
        $pag_actual = $pag_totales;
    } elseif (isset($_POST['primera_pag'])) {
        $pag_actual = 1;
    } elseif (isset($_POST['pag_siguiente']) && $pag_actual != $pag_totales) {
        $pag_actual++;
    } elseif (isset($_POST['pag_anterior']) && $pag_actual != 1) {
        $pag_actual--;
    }

    $calculo_pag = ($pag_actual * 15) - 15;
    $sql = "SELECT * FROM alumno WHERE true";
    $datos = [];

    // Filtros
    if (!empty($_POST['email'])) {
        $sql .= ' AND email LIKE :email';
        $datos[':email'] = $_POST['email'];
    }

    if (!empty($_POST['nia'])) {
        $sql .= ' AND nia LIKE :nia';
        $datos[':nia'] = $_POST['nia'];
    }

    if (!empty($_POST['telefono'])) {
        $sql .= ' AND telefono LIKE :telefono';
        $datos[':telefono'] = $_POST['telefono'];
    }

    if (!empty($_POST['nombre'])) {
        $sql .= ' AND nombre LIKE :nombre';
        $datos[':nombre'] = $_POST['nombre'];
    }

    if (!empty($_POST['cv'])) {
        $sql .= ' AND cv LIKE :cv';
        $datos[':cv'] = $_POST['cv'];
    }

    if (!empty($_POST['password'])) {
        $sql .= ' AND password LIKE :password';
        $datos[':password'] = $_POST['password'];
    }

    $sql .= ' LIMIT ' . $calculo_pag . ', 15';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($datos);
    ?>
</head>
<body>
    <form id="myForm" action="ListaAlumnos.php" method="post">
        <h2>BUSCADOR</h2>
        <label for="email">Email</label>
        <input type="text" name="email" id="email">
        <label for="nia">NIA</label>
        <input type="text" name="nia" id="nia">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre">
        <input type="submit" value="Enviar">
        <input type="reset" class="borrar" value="Reset" onclick="limpiarFormulario()">

        <script>
            function limpiarFormulario(){
                var input = document.getElementsByClassName("input")

                for(i = 0; i< input.length;i++){
                    input[i].value = ""
                }

                setTimeout(document.getElementById('myForm').submit(),0);
            }
            
                        // Función para confirmar antes de borrar un alumno
                        function confirmarBorrado(email) {
                if (confirm("¿Estás seguro de que deseas borrar este alumno?")) {
                    window.location.href = 'borrar_alumno.php?email=' + email;
                }
            }

        </script>

        <h2>TABLA ALUMNOS</h2>
        <button type="button" onclick="window.location.href = 'crear_alumno.php'">Crear</button>
        <table>
            <tr>
                <td>EMAIL</td>
                <td>NIA</td>
                <td>TELÉFONO</td>
                <td>NOMBRE</td>
                <td>OPCIONES</td>
            </tr>
            <?php while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $rows['email']; ?></td>
                    <td><?php echo $rows['nia']; ?></td>
                    <td><?php echo $rows['telefono']; ?></td>
                    <td><?php echo $rows['nombre']; ?></td>
                    <td>
                        <button type="button" onclick="window.location.href = 'modificar_alumno.php?email=<?php echo urlencode($rows['email']); ?>'">Modificar</button>
                        <button type="button" class="delete-button" onclick="confirmarBorrado('<?php echo $rows['email']; ?>')">Borrar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
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

