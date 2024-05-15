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
                                echo 'Los campos estan vacios';
        
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


                        if (!empty($_POST["btnacceder2"])){
                            if (empty($_POST["email"])){
                                echo 'Introduce un correo electrónico';
            
                            } else {
                        
                                $email=$_POST["email"];

                                $sql=$conexion->query(" select * from alumno where email='$email' ");
                    
                                    header("location:Buscador/Buscador.php");
                                    exit();
                                        echo '<div> Correo electrónico incorrecto </div>';
                                }
                            }
    


                ?>  

            </div>

            <input type="submit"  class="enviar" name="btnacceder">

            <button id="olvidar" type="button">¿Has olvidado la contraseña?</button>

            <div class="popup">
                <div class="contenido-popup">
                    <div>
                        <h1>Recupera tu cuenta</h1>

                        <h2>Introduce tu correo eléctronico para buscar tu cuenta</h2>

                    </div>
                    <input type="text" placeholder="Correo eléctronico">

                    <div class="btnolvidar">

                    <button class="cerrar">Cancelar</button>
                    <input type="submit" value="Enviar" name="btnacceder2">

                    </div>
                    
                </div>
            </div>
        
        <script>

            document.getElementById("olvidar").addEventListener("click", function(){
                document.querySelector(".popup").style.display = "flex";
            })

            document.getElementById("cerrar").addEventListener("click", function(){
                document.querySelector(".popup").style.display = "none";
            })
        </script>
</body>
</html>