<?php

session_start();

require_once 'conexion.php';

#Comprobar que haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

#Comprobar que sea administrador
if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: panel.php');
    exit;
}

#Comprobar que se recibió el ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    exit('ID de usuario no válido.');
}

#Reactivar el usuario
$sql = 'UPDATE usuarios
        SET archivado_en = NULL
        WHERE id = :id
        AND archivado_en IS NOT NULL
        AND rol = "usuario"';

$stmt = $conexion->prepare($sql);

$stmt->execute([
    'id' => $id
]);

header('Location: archivados.php');
exit;

?>