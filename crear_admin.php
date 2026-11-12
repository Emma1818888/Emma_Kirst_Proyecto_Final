<?php

require_once 'conexion.php';

$errores = [];
$mensaje = '';

#Comprobar si ya existe un administrador
$stmt = $conexion->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'admin'");

$existeAdmin = $stmt->fetchColumn();

if ($existeAdmin > 0) {
    exit('Ya existe un administrador. Este archivo ya no se puede utilizar.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

#Validar campos
    if ($nombre === '' || $apellido === '' || $dni === '' || $email === '' || $password === '') {
        $errores[] = 'Todos los campos son obligatorios.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El email no es válido.';
    }

    if (strlen($password) < 8) {
        $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    }

#Comprobar DNI y email repetidos
    if (empty($errores)) {

        $sql = 'SELECT id FROM usuarios
                WHERE dni = :dni OR email = :email';

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            'dni' => $dni,
            'email' => $email
        ]);

        if ($stmt->fetch()) {
            $errores[] = 'Ese DNI o email ya está registrado.';
        }
    }

#Crear administrador
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
                'rol' => 'admin'
            ]);

            $mensaje = 'Administrador creado correctamente.';

        } catch (PDOException $e) {

            error_log($e->getMessage());
            $errores[] = 'No se pudo crear el administrador.';

        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear administrador</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <h1>Crear administrador</h1>

    <?php foreach ($errores as $error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <?php if ($mensaje !== ''): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
        <a href="login.php">Ir al inicio de sesión</a>
    <?php else: ?>

        <form action="crear_admin.php" method="POST">

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="80" required>

            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" maxlength="80" required>

            <label for="dni">DNI</label>
            <input type="text" id="dni" name="dni" maxlength="20" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="254" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" minlength="8" required>

            <button type="submit">Crear administrador</button>

        </form>

    <?php endif; ?>

</body>
</html>