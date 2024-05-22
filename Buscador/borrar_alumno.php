<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nia = $_GET['nia'];

        // Tu código para conectar a la base de datos aquí

        $sql = "DELETE FROM alumno WHERE nia = :nia";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nia', $nia);

        try {
            $stmt->execute();
            http_response_code(200);
        } catch (PDOException $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
    }
?>
