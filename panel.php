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

    <title>Inicio - CONCERTS</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <!-- ENCABEZADO -->
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

        <!-- BIENVENIDA -->
        <section class="bienvenida">

            <h1>
                ¡Hola,
                <?php echo htmlspecialchars($usuario['nombre']); ?>!
            </h1>

            <p>Encontrá tus próximos conciertos favoritos.</p>

        </section>

        <!-- BANNER PRINCIPAL -->
        <section class="banner" style="background-image: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.75)), url('imagenes/banner.jpg');">

            <div class="banner-contenido">

                <h2>La música se vive en vivo</h2>

                <p>
                    Descubrí los mejores shows y preparate
                    para vivir experiencias inolvidables.
                </p>

                <a href="shows.php" class="boton">
                    Ver todos los shows
                </a>

            </div>

        </section>

        <!-- CONCIERTOS DESTACADOS -->
        <section>

            <h2 class="titulo-seccion">
                Próximos shows
            </h2>

            <div class="tarjetas">

                <article class="tarjeta">

                    <img src="imagenes/omar.jpg" alt="Omar Courtz">

                    <h3>Omar Courtz</h3>

                    <p>Viví su música en directo.</p>

                    <a href="detalle_show.php?show=omar" class="boton">
                        Mostrar más
                    </a>

                </article>

                <article class="tarjeta">

                    <img src="imagenes/quevedo.jpg" alt="Quevedo">

                    <h3>Quevedo</h3>

                    <p>Una noche llena de música.</p>

                    <a href="detalle_show.php?show=quevedo" class="boton">
                        Mostrar más
                    </a>

                </article>

                <article class="tarjeta">

                    <img src="imagenes/qlokura.jpg" alt="Q'Lokura">

                    <h3>Q'Lokura</h3>

                    <p>Preparáte para cantar y bailar.</p>

                    <a href="detalle_show.php?show=qlokura" class="boton">
                        Mostrar más
                    </a>

                </article>

            </div>

            <p style="text-align:center; margin-top:30px;">

                <a href="shows.php" class="boton">
                    Ver todos los shows
                </a>

            </p>

        </section>

        <!-- OFERTAS -->
        <section class="seccion-ofertas">

            <h2>¡No te pierdas ningún concierto!</h2>

            <p>
                Explorá nuestra cartelera y encontrá
                el próximo evento que querés disfrutar.
            </p>

            <a href="shows.php" class="boton">
                Explorar shows
            </a>

        </section>

    </main>

    <!-- PIE DE PÁGINA -->
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