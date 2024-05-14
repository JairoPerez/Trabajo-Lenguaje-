<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php
    
        $host = "localhost";
        $dbname = "gestionfct";
        $user = 'root';
        $pass='';


        try {
            $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            }

            catch(PDOException $e) {
        echo $e->getMessage();
            }
    
    ?>
       

</head>
<body>
    <div class = "form-body">
        <p class="text"> Iniciar sesión</p>
        <form action="login.php" method="post">

            <input type="text" name="nia" placeholder="Introduce NIA">
            <input type="password" name="password"  placeholder="Introduce contraseña">

            <div>
                <?php 
                    if (!empty($_POST["btnacceder"])){
                        if (empty($_POST["nia"]) and empty($_POST["password"])){
                                echo '<div> Los campos estan vacios </div>';
        
                        } else {
                    
                            $nia=$_POST["nia"];
                            $password=$_POST["password"];
        
                            $sql=$conexion->query(" select * from alumno where nia='$nia' and password='$password' ");
        
                            if ($datos=$sql->FETCH()){
                                session_start();

                                $_SESSION['nia']=$nia;
                                $_SESSION['nombre']=$query['nombre'];

                                header("location:Buscador/Buscador.php");
                                exit();
                                }else{
                                    echo '<div> Usuario o contraseña incorrectos </div>';
                                }
                            }
                        }
                ?>  
            </div>

            <input type="submit"  class="enviar" name="btnacceder">
            <button type="submit">¿Has olvidado la contraseña?</button>
</body>
</html>