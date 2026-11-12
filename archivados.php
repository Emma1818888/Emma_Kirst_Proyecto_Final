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

// Obtener los usuarios archivados
$sql = 'SELECT id, nombre, apellido, dni, email, creado_en, archivado_en
        FROM usuarios
        WHERE archivado_en IS NOT NULL
        AND rol = "usuario"
        ORDER BY archivado_en DESC';

$stmt = $conexion->query($sql);

$usuarios = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios archivados - CONCERTS</title>
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

    <main class="contenedor">

        <section class="bienvenida">
            <h1>Usuarios archivados</h1>
            <p>Desde acá podés consultar y reactivar usuarios.</p>
        </section>

        <?php if (count($usuarios) > 0): ?>

            <div class="tabla-caja">

                <table class="tabla-admin">

                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Fecha de archivado</th>
                        <th>Acciones</th>
                    </tr>

                    <?php foreach ($usuarios as $usuario): ?>

                        <tr>

                            <td><?php echo htmlspecialchars((string)$usuario['id']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['dni']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>

                            <td><?php echo htmlspecialchars($usuario['archivado_en']); ?></td>

                            <td>

                                <div class="acciones">

                                    <form action="reactivar_usuario.php" method="POST">

                                        <input type="hidden" name="id"
                                            value="<?php echo htmlspecialchars((string)$usuario['id']); ?>">

                                        <button type="submit" class="btn-reactivar">Reactivar</button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>

            </div>

        <?php else: ?>

            <p class="admin-vacio">No hay usuarios archivados.</p>

        <?php endif; ?>

        <p style="margin-top: 25px;">
            <a href="admin_usuarios.php" class="btn-cancelar">← Volver al panel de administración</a>
        </p>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="pie">
        <p>© 2026 CONCERTS - Todos los derechos reservados.</p>
    </footer>

</body>

</html>