<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$shows = [
    'omar' => [
        'nombre' => 'Omar Courtz',
        'imagen' => 'omar.jpg',
        'descripcion' => 'Disfrutá una noche llena de reggaetón y música urbana.'
    ],
    'quevedo' => [
        'nombre' => 'Quevedo',
        'imagen' => 'quevedo.jpg',
        'descripcion' => 'Viví en directo los grandes éxitos de Quevedo.'
    ],
    'qlokura' => [
        'nombre' => "Q'Lokura",
        'imagen' => 'qlokura.jpg',
        'descripcion' => 'Una noche para cantar y bailar al ritmo del cuarteto.'
    ],
    'luismiguel' => [
        'nombre' => 'Luis Miguel',
        'imagen' => 'luismiguel.jpg',
        'descripcion' => 'Disfrutá una noche con sus canciones más conocidas.'
    ],
    'duki' => [
        'nombre' => 'Duki',
        'imagen' => 'duki.webp',
        'descripcion' => 'Viví toda la energía del trap y la música urbana.'
    ],
    'delarose' => [
        'nombre' => 'De La Rose',
        'imagen' => 'delarose.avif',
        'descripcion' => 'Disfrutá una noche de música urbana en vivo.'
    ]
];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shows - CONCERTS</title>
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

        <h1 class="titulo-seccion">Todos los shows</h1>

        <div class="tarjetas">

            <?php foreach ($shows as $clave => $show) { ?>

                <article class="tarjeta">

                    <img src="imagenes/<?php echo $show['imagen']; ?>" alt="<?php echo htmlspecialchars($show['nombre']); ?>">

                    <h3><?php echo htmlspecialchars($show['nombre']); ?></h3>

                    <p><?php echo htmlspecialchars($show['descripcion']); ?></p>

                    <a href="detalle_show.php?show=<?php echo $clave; ?>" class="boton">
                        Mostrar más
                    </a>

                </article>

            <?php } ?>

        </div>

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
