<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="styleCrear.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Alumno</title>
</head>
<body>
    <h2>Modificar Alumno</h2>

    <?php
    $host = 'localhost';
    $dbname = 'gestionfct';
    $user = 'root';
    $pass = '';

    $email = $_GET['email'] ?? null;

    if ($email) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * FROM alumno WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($alumno) {
                $nia = $alumno['nia'];
                $telefono = $alumno['telefono'];
                $nombre = $alumno['nombre'];
                $cv_file = $alumno['cv_file'];
                $password = $alumno['password'];
            } else {
                echo "No se encontró el alumno con email $email";
            }
        } catch (PDOException $e) {
            echo "Error al intentar modificar un alumno." ;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $nia = $_POST['nia'];
        $telefono = $_POST['telefono'];
        $nombre = $_POST['nombre'];
        $cv_file = isset($_FILES['cv_file']['name']) ? $_FILES['cv_file']['name'] : '';
        $password = $_POST['password'];

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE alumno SET nombre = :nombre, email = :new_email, telefono = :telefono, cv_file = :cv_file, password = :password, nia = :nia WHERE email = :old_email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':new_email', $email);
            $stmt->bindParam(':old_email', $_POST['old_email']);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':cv_file', $cv_file);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':nia', $nia);
            $stmt->execute();

            echo "Alumno modificado con éxito!";
        } catch (PDOException $e) {
            echo "Error al intentar modificar un alumno. ";
        }
    }
    ?>
    
<form action="modificar_alumno.php" method="post" enctype="multipart/form-data">
    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="<?= $email ?? ''; ?>">
    <!-- Mostrar el email del alumno, si existe -->

    <label for="nia">NIA</label>
    <input type="text" name="nia" id="nia" value="<?= $nia ?? ''; ?>">
    <!-- Mostrar el NIA del alumno, si existe -->

    <label for="telefono">Teléfono</label>
    <input type="text" name="telefono" id="telefono" value="<?= $telefono ?? ''; ?>">
    <!-- Mostrar el teléfono del alumno, si existe -->

    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" value="<?= $nombre ?? ''; ?>">
    <!-- Mostrar el nombre del alumno, si existe -->

    <label for="cv_file">CV File</label>
    <input type="file" name="cv_file" id="cv_file">

    <label for="password">Password</label>
    <input type="password" name="password" id="password" value="<?= $password ?? ''; ?>">
    <!-- Mostrar la contraseña del alumno, si existe -->

    <input type="hidden" name="old_email" value="<?= $email ?? ''; ?>">
    <!-- Almacenar el email original del alumno -->

    <!-- Botón para enviar el formulario -->
    <input type="submit" value="Modificar">

    <!-- Botón para volver a la lista de alumnos -->
    <input type="button" value="Volver" onclick="location.href='ListaAlumnos.php'">
</form>

</body>
</html>