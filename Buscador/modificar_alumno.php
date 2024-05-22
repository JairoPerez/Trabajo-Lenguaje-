<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Alumno</title>
</head>
<body>
    <h2>Modificar Alumno</h2>
    <form action="" method="post">
        <label for="email">Email</label>
        <input type="text" name="email" id="email">

        <label for="nia">Nia</label>
        <input type="text" name="nia" id="nia">

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono">

        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre">

        <label for="cv_file">CV File</label>
        <input type="file" name="cv_file" id="cv_file">

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <input type="submit" value="Modificar">
        <input type="button" value="Volver" onclick="location.href='Buscador.php'">
    </form>

    <?php
        include '..\auth.php';

        $host='localhost';
        $dbname='gestionfct';
        $user='root';
        $pass='';

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $email = $_POST['email'];
            $nia = $_POST['nia'];
            $telefono = $_POST['telefono'];
            $nombre = $_POST['nombre'];
            $cv_file = $_POST['cv_file'];
            $password = $_POST['password'];

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "UPDATE alumno SET nombre = :nombre, email = :email, nia = :nia, telefono = :telefono, cv_file = :cv_file WHERE nia = :nia";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':nia', $nia);
                $stmt->bindParam(':telefono', $telefono);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':cv_file', $cv_file);
                $stmt->execute();

                if ($stmt->execute()) {
                    echo "Alumno modificado con éxito!";
                } else {
                    print_r($stmt->errorInfo());
                }
            } catch(PDOException $e) {
                echo $sql . "<br>" . $e->getMessage();
            }

            $pdo = null;
        }
    ?>
</body>
</html>