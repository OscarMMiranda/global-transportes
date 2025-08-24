<?php
session_start();
include '../includes/conexion.php';

// Solo admin puede acceder
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {

    header("Location: http://www.globaltransportes.com/login");

    // header("Location: login.php");
    exit();
}

$mensaje = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $usuario = trim($_POST["usuario"]);
    $correo = trim($_POST["correo"]);
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
    $rol = $_POST["rol"];

    // Validación simple
    if ($nombre && $apellido && $usuario && $correo && $contrasena && $rol) {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nombre, $apellido, $usuario, $correo, $contrasena, $rol);

        if ($stmt->execute()) {
            $mensaje = "Usuario creado correctamente.";
        } else {
            $mensaje = "Error al crear usuario: " . $conn->error;
        }

        $stmt->close();
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="login-form">
        <h2>Crear Nuevo Usuario</h2>

        <?php if ($mensaje): ?>
            <p style="color: #004a99; font-weight: bold;"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <form class="formulario" method="post">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>

            <select name="rol" required>
                <option value="" disabled selected>Selecciona un rol</option>
                <option value="admin">Administrador</option>
                <option value="chofer">Chofer</option>
                <option value="cliente">Cliente</option>
            </select>

            <button type="submit">Crear Usuario</button>
        </form>

        <p style="text-align:center; margin-top:1rem;">
            <a href="usuarios.php" class="boton-accion">Volver a Usuarios</a>
        </p>
    </div>
</body>
</html>
