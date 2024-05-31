<?php
$host = 'localhost';
$dbname = 'gestionfct';
$user = 'root';
$pass = '';

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recupera el email del alumno a borrar de la URL
    $email = $_GET['email'];

    // Ejecuta la consulta para eliminar al alumno
    $stmt = $pdo->prepare("DELETE FROM alumno WHERE email = :email");
    $stmt->execute([':email' => $email]);

    // Redirecciona de nuevo a la página principal
    header('Location: ListaAlumnos.php');
    exit();
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

