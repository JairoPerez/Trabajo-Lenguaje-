<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Alumno</title>
</head>
<body>
    <h2>Crear Alumno</h2>
    <form action="" method="post">
        <label for="email">Email</label>
        <input type="text" name="email" id="email">

        <label for="nia">Nia</label>
        <input type="text" name="nia" id="nia">

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono">

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre">

        <input type="submit" value="Crear">
    </form>

    <?php
        include '..\auth.php';

        $host='localhost';
        $dbname='gestionfct';
        $user='root';
        $pass='';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $nia = $_POST['nia'];
            $telefono = $_POST['telefono'];
            $nombre = $_POST['nombre'];

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "INSERT INTO alumno (email, nia, telefono, nombre) VALUES (:email, :nia, :telefono, :nombre)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':nia', $nia);
                $stmt->bindParam(':telefono', $telefono);
                $stmt->bindParam(':nombre', $nombre);

                $stmt->execute();
                echo "Alumno creado con éxito!";
            } catch(PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }

            $pdo = null;
        }
    ?>
</body>
</html>
