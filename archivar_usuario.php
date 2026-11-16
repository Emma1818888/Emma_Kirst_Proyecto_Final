<?php

session_start();

require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: panel.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    exit('ID de usuario no válido.');
}

$sql = 'UPDATE usuarios
        SET archivado_en = CURRENT_TIMESTAMP
        WHERE id = :id
        AND rol = "usuario"
        AND archivado_en IS NULL';

$stmt = $conexion->prepare($sql);

$stmt->execute([
    'id' => $id
]);

header('Location: admin_usuarios.php');
exit;

?>
