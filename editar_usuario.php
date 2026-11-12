<?php

session_start();

require_once 'conexion.php';

// Comprobar que haya iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Comprobar que sea administrador
if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: panel.php');
    exit;
}

// Comprobar que se haya recibido un ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    exit('ID de usuario no válido.');
}

// Buscar el usuario
$sql = 'SELECT id, nombre, apellido, dni, email
        FROM usuarios
        WHERE id = :id
        AND rol = "usuario"
        AND archivado_en IS NULL';

$stmt = $conexion->prepare($sql);
$stmt->execute(['id' => $id]);

$usuario = $stmt->fetch();

if (!$usuario) {
    exit('No se encontró el usuario.');
}

// Nombre original, para el subtítulo (no cambia aunque haya errores)
$nombreOriginal = $usuario['nombre'] . ' ' . $usuario['apellido'];

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validar campos
    if ($nombre === '' || $apellido === '' || $dni === '' || $email === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El email no es válido.';
    }

    // Comprobar DNI y email repetidos en otras cuentas
    if (empty($errores)) {

        $sql = 'SELECT id FROM usuarios
                WHERE (dni = :dni OR email = :email)
                AND id != :id';

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            'dni' => $dni,
            'email' => $email,
            'id' => $id
        ]);

        if ($stmt->fetch()) {
            $errores[] = 'El DNI o email ya pertenece a otro usuario.';
        }
    }

    // Actualizar los datos
    if (empty($errores)) {

        $sql = 'UPDATE usuarios
                SET nombre = :nombre,
                    apellido = :apellido,
                    dni = :dni,
                    email = :email
                WHERE id = :id
                AND rol = "usuario"';

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'email' => $email,
            'id' => $id
        ]);

        header('Location: admin_usuarios.php');
        exit;
    }

    $usuario['nombre'] = $nombre;
    $usuario['apellido'] = $apellido;
    $usuario['dni'] = $dni;
    $usuario['email'] = $email;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario - CONCERTS</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <!-- ENCABEZADO -->
    <header class="encabezado">

        <a href="admin_usuarios.php" class="logo">CONCERTS</a>

        <nav class="navegacion">
            <a href="admin_usuarios.php">Usuarios</a>
            <a href="registro.php">Registrar un usuario</a>
            <a href="archivados.php">Archivados</a>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>

    </header>

    <main class="auth-pagina">

        <div class="auth-caja auth-caja-ancha">

            <h1>Editar usuario</h1>
            <p class="auth-subtitulo">
                Estás editando a <strong><?php echo htmlspecialchars($nombreOriginal); ?></strong>
                (ID <?php echo $id; ?>).
            </p>

            <?php foreach ($errores as $error): ?>
                <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>

            <form action="editar_usuario.php?id=<?php echo $id; ?>" method="POST">

                <div class="fila-doble">

                    <div>
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre"
                            value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                            maxlength="80" required>
                    </div>

                    <div>
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido"
                            value="<?php echo htmlspecialchars($usuario['apellido']); ?>"
                            maxlength="80" required>
                    </div>

                </div>

                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni"
                    value="<?php echo htmlspecialchars($usuario['dni']); ?>"
                    maxlength="20" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    value="<?php echo htmlspecialchars($usuario['email']); ?>"
                    maxlength="254" required>

                <div class="botones-formulario">
                    <button type="submit">Guardar cambios</button>
                    <a href="admin_usuarios.php" class="btn-cancelar">Cancelar</a>
                </div>

            </form>

        </div>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="pie">
        <p>© 2026 CONCERTS - Todos los derechos reservados.</p>
    </footer>

</body>

</html>