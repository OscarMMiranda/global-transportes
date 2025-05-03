<?php
session_start();
include '../includes/conexion.php';

// Solo admins pueden acceder
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = "";
$exito = "";

// Obtener roles disponibles desde la tabla 'roles'
$roles_result = $conn->query("SELECT id, nombre FROM roles");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    $rol = (int) $_POST['rol'];

    // Validar duplicados
    $verificar = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR correo = ?");
    $verificar->bind_param("ss", $usuario, $correo);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        $error = "❌ El nombre de usuario o correo ya existe.";
    } else {
        $sql = $conn->prepare("INSERT INTO usuarios (nombre, apellido, usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("sssssi", $nombre, $apellido, $usuario, $correo, $clave, $rol);

        if ($sql->execute()) {
            $exito = "✅ Usuario creado correctamente.";
        } else {
            $error = "❌ Error al crear el usuario.";
        }

        $sql->close();
    }

    $verificar->close();
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
<div class="container">
    <h1>Crear Usuario</h1>

    <?php if ($error): ?>
        <div class="mensaje-sistema" style="background-color: #fee; color: #a00; padding: 10px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php elseif ($exito): ?>
        <div class="mensaje-sistema" style="background-color: #e6ffed; color: #007700; padding: 10px;">
            <?= htmlspecialchars($exito) ?>
        </div>
    <?php endif; ?>

    <form action="crear_usuario.php" method="POST" class="login-form">
        <div class="formulario">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="text" name="usuario" placeholder="Nombre de usuario" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            
            <select name="rol" required>
                <option value="">-- Selecciona un rol --</option>
                <?php while ($row = $roles_result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= ucfirst(htmlspecialchars($row['nombre'])) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Crear Usuario</button>
        </div>
    </form>

    <a href="usuarios.php" class="boton-accion">← Volver a la lista</a>
</div>
</body>
</html>

