<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Alumno</title>
</head>
<body>
    <h2>Modificar Alumno</h2>

    <?php
    // Inclusión del archivo de autenticación y configuración de la base de datos
    include '../auth.php';

    $host = 'localhost';
    $dbname = 'gestionfct';
    $user = 'root';
    $pass = '';

    // Obtener el NIA del alumno a modificar si se proporciona en la URL
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['nia'])) {
        $nia = $_GET['nia'];

        try {
            // Conexión a la base de datos
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta para obtener los datos del alumno
            $sql = "SELECT * FROM alumno WHERE nia = :nia";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nia', $nia, PDO::PARAM_INT);
            $stmt->execute();
            $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($alumno) {
                // Asignar los datos del alumno a variables
                $email = $alumno['email'];
                $telefono = $alumno['telefono'];
                $nombre = $alumno['nombre'];
                $cv_file = $alumno['cv_file']; 
            } else {
                echo "No se encontró el alumno con NIA $nia.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Procesar la modificación del alumno cuando se envía el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $nia = $_POST['nia'];
        $telefono = $_POST['telefono'];
        $nombre = $_POST['nombre'];
        // Agregar manejo de archivos aquí si es necesario
        $cv_file = isset($_FILES['cv_file']['name']) ? $_FILES['cv_file']['name'] : '';
        $password = $_POST['password'];

        try {
            // Conexión a la base de datos
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta para actualizar los datos del alumno
            $sql = "UPDATE alumno SET nombre = :nombre, email = :email, telefono = :telefono, cv_file = :cv_file WHERE nia = :nia";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nia', $nia);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':cv_file', $cv_file);
            $stmt->execute();

            echo "Alumno modificado con éxito!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>
    
    <!-- Formulario HTML para modificar el alumno -->
    <form action="modificar_alumno.php" method="post" enctype="multipart/form-data">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">

        <label for="nia">Nia</label>
        <input type="text" name="nia" id="nia" value="<?php echo isset($nia) ? htmlspecialchars($nia) : ''; ?>" >

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>">

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>">

        <label for="cv_file">CV File</label>
        <input type="file" name="cv_file" id="cv_file">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <input type="submit" value="Modificar">
        <input type="button" value="Volver" onclick="location.href='Buscador.php'">
    </form>
</body>
</html>
