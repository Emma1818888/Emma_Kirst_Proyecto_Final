<?php

require_once 'conexion.php';

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // recibe los datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if ($nombre === '' || $apellido === '' || $dni === '' || $email === '' || $password === '' || $confirmar === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico no es válido.';
    }

    if ($password !== $confirmar) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    if (empty($errores)) {

        $sql = 'SELECT id FROM usuarios WHERE dni = :dni OR email = :email';

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            'dni' => $dni,
            'email' => $email
        ]);

        if ($stmt->fetch()) {
            $errores[] = 'Ese DNI o correo electrónico ya está registrado.';
        }
    }

    if (empty($errores)) {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO usuarios
                (nombre, apellido, dni, email, password_hash, rol)
                VALUES
                (:nombre, :apellido, :dni, :email, :password_hash, :rol)';

        try {

            $stmt = $conexion->prepare($sql);

            $stmt->execute([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'dni' => $dni,
                'email' => $email,
                'password_hash' => $passwordHash,
                'rol' => 'usuario'
            ]);

            $mensaje = '¡Tu cuenta fue creada correctamente!';

        } catch (PDOException $e) {

            error_log($e->getMessage());
            $errores[] = 'No se pudo completar el registro. Revisá los datos e intentá nuevamente.';

        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - CONCERTS</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <main class="auth-pagina">

        <a href="login.php" class="logo">CONCERTS</a>

        <div class="auth-caja">

            <h1>Crear una cuenta</h1>
            <p class="auth-subtitulo">Registrate para descubrir los mejores shows.</p>

            <?php if (!empty($errores)): ?>
                <?php foreach ($errores as $error): ?>
                    <p class="mensaje-error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($mensaje !== ''): ?>
                <p class="mensaje-exito">
                    <?php echo htmlspecialchars($mensaje); ?>
                    <a href="login.php">Iniciar sesión</a>
                </p>
            <?php endif; ?>

            <form action="registro.php" method="POST">

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" maxlength="80" placeholder="Tu nombre" required>

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" maxlength="80" placeholder="Tu apellido" required>

                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" maxlength="20" placeholder="Sin puntos" required>

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" maxlength="254" placeholder="tucorreo@ejemplo.com" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>

                <label for="confirmar">Confirmar contraseña</label>
                <input type="password" id="confirmar" name="confirmar" placeholder="Repetí tu contraseña" required>

                <button type="submit">Crear cuenta</button>

            </form>

            <p class="auth-enlace">
                ¿Ya tenés una cuenta?
                <a href="login.php">Iniciar sesión</a>
            </p>

        </div>

    </main>

</body>
</html>
