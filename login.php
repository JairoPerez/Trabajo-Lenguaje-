<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <?php
        $host = "localhost";
        $dbname = "universidad";
        $user = 'root';
        $pass='';

        $sql="select * from alumno where true";
            $datos=[];

            if(!empty($dni)){
                $sql.=' and dni like :dni';
                $datos[':dni']=$dni;
            }

            if(!empty($nombre)){
                $sql.=' and nombre like :nombre';
                $datos[':nombre']=$nombre;
            }

            try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            $stmt=$pdo->prepare($sql);
            $stmt->execute($datos);
        
            }

            catch(PDOException $e) {
                echo $e->getMessage();
            }


            

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST['username'];
                $password = $_POST['password'];
                 // Realizar una consulta para verificar si el usuario existe y la contraseña es correcta
                $sql = "SELECT id, username FROM usuarios WHERE username='$username' AND password='$password'";
                $result = $conn->query($sql);

                if ($result->num_rows == 1) {
                // Usuario autenticado correctamente
                $_SESSION['username'] = $username;
                // Redirigir a la página de inicio o a donde desees
                header("Location: Trabajo.php");
                exit();
                } else {
             // Usuario o contraseña incorrectos
                echo "Usuario o contraseña incorrectos";
            }
        }


    ?>

</head>
<body>
    <div class = "form-body">
        <p class="text"> Login FCT </p>
        <form class="form-login" action="login.php" method="post">
            
            <input type="text" placeholder="Introduce NIA">
            <input type="password" placeholder="Introduce contraseña">

            <button type="submit">Iniciar sesión</button>
            <button type="submit">¿Has olvidado la contraseña?</button>

        </form>
    </div>    
</body>
</html>