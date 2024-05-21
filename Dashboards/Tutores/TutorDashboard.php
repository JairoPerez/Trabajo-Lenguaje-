<!DOCTYPE html>
<html lang="en">
    <head>
    <link rel="stylesheet" href="tutor.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=H, initial-scale=1.0">
    <title>Document</title>
    <?php
    include '..\..\auth.php';
    ?>
   
    </head>
    <body>
        <div class="sidebar">
            <div class="logo"></div>
            <ul class="menu">
                <li class="active">
                    <a href="#">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="..\Menus\Perfil\Perfil.php">
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="..\Menus\ListaAlumnos\ListaAlumnos.php">
                        <span>Alumnos</span>
                    </a>
                </li>
                <li>
                    <a href="..\Menus\Mensajes\Mensajes.php">
                        <span>Mensajes</span>
                    </a>
                </li>
                <li>
                    <a href="..\Menus\Empresas\Empresas.php">
                        <span>Empresas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span>Ajustes</span>
                    </a>
                </li>
                <li class="logout">
                    <a href="#">
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </body>
</html>