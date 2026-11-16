<?php

session_start();

require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$sql = 'SELECT nombre, apellido, dni, email
        FROM usuarios
        WHERE id = :id
        AND archivado_en IS NULL';

$stmt = $conexion->prepare($sql);
$stmt->execute(['id' => $_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if (!$usuario) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi perfil - CONCERTS</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header class="encabezado">

        <a href="panel.php" class="logo">CONCERTS</a>

        <nav class="navegacion">
            <a href="panel.php">Inicio</a>
            <a href="shows.php">Shows</a>
            <a href="perfil.php">Mi perfil</a>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>

    </header>

    <main class="contenedor">

        <h1 class="titulo-seccion">Mi perfil</h1>

        <section class="perfil">

            <div class="perfil-dato">
                <span>NOMBRE</span>
                <p><?php echo htmlspecialchars($usuario['nombre']); ?></p>
            </div>

            <div class="perfil-dato">
                <span>APELLIDO</span>
                <p><?php echo htmlspecialchars($usuario['apellido']); ?></p>
            </div>

            <div class="perfil-dato">
                <span>DNI</span>
                <p><?php echo htmlspecialchars($usuario['dni']); ?></p>
            </div>

            <div class="perfil-dato">
                <span>EMAIL</span>
                <p><?php echo htmlspecialchars($usuario['email']); ?></p>
            </div>

        </section>

    </main>

    <footer class="pie">

        <h3>CONCERTS</h3>

        <p>Viví la música en vivo.</p>

        <p>Contacto: contacto@concerts.com</p>

        <p>Teléfono: 11 1234-5678</p>

        <p>Instagram | Facebook | TikTok | YouTube</p>

        <p>© 2026 CONCERTS - Todos los derechos reservados.</p>

    </footer>

</body>

</html>
