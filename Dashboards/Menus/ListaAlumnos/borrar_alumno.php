<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera el email (Número de Identificación del Alumno) del formulario POST.
    // Si no se encuentra, lo establece como null.
    $email = $_POST['email'] ?? null;

    // Verifica si el email fue proporcionado.
    if ($email) {
        // Datos de conexión a la base de datos.
        $host = 'localhost';
        $dbname = 'gestionfct';
        $user = 'root';
        $pass = '';

        try {
            // Crea una nueva instancia de PDO para conectarse a la base de datos.
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            // Configura PDO para que lance excepciones en caso de error.
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta para verificar si el alumno con el email especificado existe.
            $sql_check = "SELECT COUNT(*) FROM alumno WHERE email = :email";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':email', $email, PDO::PARAM_INT);
            $stmt_check->execute();
            // Recupera el número de filas que coinciden con el email.
            $count = $stmt_check->fetchColumn();

            // Si el alumno no existe, devuelve un código de respuesta 404 (no encontrado) y un mensaje.
            if ($count == 0) {
                http_response_code(404);
                echo "No se encontró el alumno con email $email.";
                exit;
            }

            // Consulta para eliminar al alumno con el email especificado.
            $sql = "DELETE FROM alumno WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_INT);
            $stmt->execute();

            // Verifica si la eliminación tuvo éxito (afectó al menos una fila).
            if ($stmt->rowCount()) {
                // Si tuvo éxito, devuelve un código de respuesta 200 (OK) y un mensaje.
                http_response_code(200);
                echo "Alumno borrado correctamente.";
            } else {
                // Si no tuvo éxito, devuelve un código de respuesta 500 (error interno del servidor) y un mensaje.
                http_response_code(500);
                echo "Error al intentar borrar el alumno con email $email.";
            }
        } catch (PDOException $e) {
            // Si ocurre una excepción de PDO, devuelve un código de respuesta 500 y el mensaje de error.
            http_response_code(500);
            echo "Error de base de datos: " . $e->getMessage();
        }
    } else {
        // Si no se proporcionó el email, devuelve un código de respuesta 400 (solicitud incorrecta) y un mensaje.
        http_response_code(400);
        echo "email no especificado.";
    }
}
?>
