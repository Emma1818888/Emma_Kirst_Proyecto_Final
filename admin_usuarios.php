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

$busqueda = trim($_GET['buscar'] ?? '');

$sql = 'SELECT id, nombre, apellido, dni, email, creado_en
        FROM usuarios
        WHERE archivado_en IS NULL
        AND rol = "usuario"';

$parametros = [];

if ($busqueda !== '') {

    $sql .= ' AND (
        nombre LIKE :nombre
        OR apellido LIKE :apellido
        OR dni LIKE :dni
        OR email LIKE :email
    )';

    $parametros = [
        'nombre' => "%$busqueda%",
        'apellido' => "%$busqueda%",
        'dni' => "%$busqueda%",
        'email' => "%$busqueda%"
    ];
}

$sql .= ' ORDER BY id DESC';

$stmt = $conexion->prepare($sql);
$stmt->execute($parametros);

$usuarios = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administración - CONCERTS</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <header class="encabezado">

        <a href="admin_usuarios.php" class="logo">CONCERTS</a>

        <nav class="navegacion">
            <a href="admin_usuarios.php">Usuarios</a>
            <a href="registro.php">Registrar un usuario</a>
            <a href="archivados.php">Archivados</a>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </nav>

    </header>

    <main class="contenedor">

        <section class="bienvenida">
            <h1>Panel de administración</h1>
            <p>Bienvenido/a, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
        </section>

        <div class="admin-barra">

            <h2 class="titulo-seccion">Usuarios registrados</h2>

            <form action="admin_usuarios.php" method="GET" class="buscador">

                <input
                    type="text"
                    id="buscar"
                    name="buscar"
                    aria-label="Buscar usuario"
                    placeholder="Buscar por nombre, apellido, DNI o email"
                    value="<?php echo htmlspecialchars($busqueda); ?>"
                >

                <button type="submit">Buscar</button>

                <?php if ($busqueda !== ''): ?>
                    <a href="admin_usuarios.php" class="buscador-limpiar">Limpiar búsqueda</a>
                <?php endif; ?>

            </form>

        </div>

        <?php if (count($usuarios) > 0): ?>

            <div class="tabla-caja">

                <table class="tabla-admin">

                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Fecha de creación</th>
                        <th>Acciones</th>
                    </tr>

                    <?php foreach ($usuarios as $usuario): ?>

                        <tr>

                            <td><?php echo htmlspecialchars((string)$usuario['id']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['dni']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['creado_en']); ?></td>

                            <td>

                                <div class="acciones">

                                    <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn-editar">Editar</a>

                                    <form action="archivar_usuario.php" method="POST">

                                        <input type="hidden" name="id"
                                            value="<?php echo htmlspecialchars((string)$usuario['id']); ?>">

                                        <button type="submit">Archivar</button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>

            </div>

        <?php else: ?>

            <p class="admin-vacio">
                <?php echo $busqueda !== '' ? 'No se encontraron usuarios con esa búsqueda.' : 'Todavía no hay usuarios registrados.'; ?>
            </p>

        <?php endif; ?>

    </main>

    <footer class="pie">
        <p>© 2026 CONCERTS - Todos los derechos reservados.</p>
    </footer>

</body>

</html>
