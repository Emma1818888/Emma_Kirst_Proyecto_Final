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

$artista = $_GET['show'] ?? $_GET['id'] ?? 'omar';

if (!isset($shows[$artista])) {
    header('Location: shows.php');
    exit;
}

$nombre = $shows[$artista]['nombre'];
$imagen = $shows[$artista]['imagen'];
$descripcion = $shows[$artista]['descripcion'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($nombre); ?> - CONCERTS</title>
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

    <main class="detalle-pagina">

        <p class="detalle-superior">DESCUBRÍ TU PRÓXIMO SHOW</p>

        <div class="detalle-artista">

            <div class="detalle-imagen">
                <img src="imagenes/<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($nombre); ?>">
            </div>

            <div class="detalle-contenido">

                <p class="detalle-categoria">EVENTO DESTACADO</p>

                <h1><?php echo htmlspecialchars($nombre); ?></h1>

                <div class="detalle-linea"></div>

                <p class="detalle-descripcion">
                    <?php echo htmlspecialchars($descripcion); ?>
                </p>

                <div class="detalle-informacion">

                    <div>
                        <span>FECHA</span>
                        <p>A confirmar</p>
                    </div>

                    <div>
                        <span>LUGAR</span>
                        <p>A confirmar</p>
                    </div>

                    <div>
                        <span>ENTRADAS</span>
                        <p>Próximamente</p>
                    </div>

                </div>

                <a href="comprar.php?show=<?php echo $artista; ?>" class="boton">
                 Comprar entradas
                </a>

                <a href="shows.php" class="detalle-regresar">
                    ← Volver a los shows
                </a>

            </div>

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
