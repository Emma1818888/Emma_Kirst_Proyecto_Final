<?php

session_start();

require_once 'conexion.php';

$errores = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {

        $errores = 'Completá todos los campos.';

    } else {

        $sql = 'SELECT id, nombre, email, password_hash, rol
                FROM usuarios
                WHERE email = :email
                AND archivado_en IS NULL
                LIMIT 1';

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            'email' => $email
        ]);

        $usuario = $stmt->fetch();

        // comprueba la contraseña
        if ($usuario && password_verify($password, $usuario['password_hash'])) {

            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            if ($usuario['rol'] === 'admin') {

                header('Location: admin_usuarios.php');

            } else {

                header('Location: panel.php');

            }

            exit;

        } else {

            $errores = 'Email o contraseña incorrectos.';

        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - CONCERTS</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <main class="auth-pagina">

        <a href="login.php" class="logo">CONCERTS</a>

        <div class="auth-caja">

            <h1>Iniciar sesión</h1>
            <p class="auth-subtitulo">Ingresá para ver tus próximos conciertos.</p>

            <?php if ($errores !== ''): ?>
                <p class="mensaje-error"><?php echo htmlspecialchars($errores); ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="tucorreo@ejemplo.com" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>

                <button type="submit">Ingresar</button>

            </form>

            <p class="auth-enlace">
                ¿Todavía no tenés cuenta?
                <a href="registro.php">Registrate</a>
            </p>

        </div>

    </main>

</body>
</html>