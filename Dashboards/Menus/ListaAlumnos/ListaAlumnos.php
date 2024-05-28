<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCT</title>
    <?php
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
        <input type="reset" id="resetInput" value="Reset">
        <script>
            // Añade un evento al botón con id 'resetInput' que se ejecutará al hacer clic en él
            document.getElementById('resetInput').addEventListener('click', function() {
                // Recarga la página actual
                location.reload();
            });

            // Añade un evento al formulario con id 'myForm' que se ejecutará cuando se restablezca el formulario
            document.getElementById('myForm').addEventListener('reset', function() {
                // Utiliza setTimeout para ejecutar una función después de que el evento 'reset' se haya completado
                setTimeout(function() {
                    // Envía el formulario automáticamente
                    document.getElementById('myForm').submit();
                }, 0); // El retardo es 0 milisegundos, por lo que se ejecuta inmediatamente después del 'reset'
            });
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
                        <button type="button" class="delete-button" data-email="<?php echo $rows['email']; ?>">Borrar</button>
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
    <script>
    // Selecciona todos los elementos con la clase 'delete-button' y aplica una función a cada uno
    document.querySelectorAll('.delete-button').forEach(function(button) {
        // Añade un evento de clic a cada botón de eliminar
        button.addEventListener('click', function(e) {
            // Muestra un cuadro de confirmación y si el usuario confirma, continúa con la eliminación
            if (confirm('¿Está seguro?')) {
                // Obtiene el valor del atributo 'data-email' del botón que fue clicado
                var email = e.target.getAttribute('data-email');
                
                // Realiza una solicitud HTTP a 'borrar_alumno.php' utilizando el método POST
                fetch('borrar_alumno.php', {
                    method: 'POST', // Define el método HTTP como POST
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded', // Define el tipo de contenido de la solicitud
                    },
                    body: 'email=' + email // Define el cuerpo de la solicitud, enviando el email del alumno a borrar
                }).then(function(response) {
                    // Convierte la respuesta a texto
                    return response.text().then(function(text) {
                        // Si la respuesta del servidor es exitosa (código HTTP 200-299)
                        if (response.ok) {
                            // Elimina la fila del alumno de la tabla en el DOM
                            e.target.parentElement.parentElement.remove();
                        } else {
                            // Muestra un mensaje de error si la respuesta no es exitosa
                            alert('Error: ' + text);
                        }
                    });
                }).catch(function(error) {
                    // Muestra un mensaje de error si hay problemas con la solicitud
                    alert('Error al conectar con el servidor.');
                });
            }
        });
    });
</script>

</body>
</html>
