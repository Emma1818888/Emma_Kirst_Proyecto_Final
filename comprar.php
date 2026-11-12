<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$show = $_POST['show'] ?? $_GET['show'] ?? 'omar';

$artistas = [
    'omar' => [
        'nombre' => 'Omar Courtz',
        'imagen' => 'omar.jpg'
    ],
    'quevedo' => [
        'nombre' => 'Quevedo',
        'imagen' => 'quevedo.jpg'
    ],
    'qlokura' => [
        'nombre' => "Q'Lokura",
        'imagen' => 'qlokura.jpg'
    ],
    'luismiguel' => [
        'nombre' => 'Luis Miguel',
        'imagen' => 'luismiguel.jpg'
    ],
    'duki' => [
        'nombre' => 'Duki',
        'imagen' => 'duki.webp'
    ],
    'delarose' => [
        'nombre' => 'De La Rose',
        'imagen' => 'delarose.avif'
    ]
];

if (!isset($artistas[$show])) {
    $show = 'omar';
}

$artista = $artistas[$show];

$comprado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cantidad = (int) ($_POST['cantidad'] ?? 0);

    if ($nombre !== '' && $apellido !== '' &&
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        $cantidad >= 1 && $cantidad <= 10) {
        $comprado = true;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar entradas - CONCERTS</title>
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

    <?php if ($comprado): ?>

        <section class="compra-finalizada">

            <div class="compra-foto">
                <img src="imagenes/<?php echo htmlspecialchars($artista['imagen']); ?>"
                     alt="<?php echo htmlspecialchars($artista['nombre']); ?>">
            </div>

            <div class="compra-info">
                <h1>¡Compra realizada!</h1>

                <p class="compra-subtitulo">
                    Tu compra se realizó correctamente.
                </p>

                <h2><?php echo htmlspecialchars($artista['nombre']); ?></h2>

                <p><strong>Nombre:</strong>
                    <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?>
                </p>

                <p><strong>Correo:</strong>
                    <?php echo htmlspecialchars($email); ?>
                </p>

                <p><strong>Entradas:</strong>
                    <?php echo $cantidad; ?>
                </p>

                <p class="compra-gracias">
                    ¡Gracias por elegir CONCERTS!
                </p>

                <a href="shows.php" class="boton">Volver a los shows</a>
            </div>

        </section>

    <?php else: ?>

        <section class="formulario-compra">

            <h1>Comprar entradas</h1>

            <p>Estás por comprar entradas para:</p>

            <h2><?php echo htmlspecialchars($artista['nombre']); ?></h2>

            <form action="comprar.php" method="POST">

                <input type="hidden" name="show"
                       value="<?php echo htmlspecialchars($show); ?>">

                <label>Nombre</label>
                <input type="text" name="nombre" required>

                <label>Apellido</label>
                <input type="text" name="apellido" required>

                <label>Correo electrónico</label>
                <input type="email" name="email" required>

                <label>Cantidad de entradas</label>
                <input type="number" name="cantidad" min="1" max="10" required>

                <button type="submit" class="boton">
                    Confirmar compra
                </button>

            </form>

        </section>

    <?php endif; ?>

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