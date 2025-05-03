<?php
session_start();
include '../includes/conexion.php';

// Solo admins pueden acceder
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";
$usuario = null;

// Obtener datos del usuario si ID válido
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt->close();
}

if (!$usuario) {
    echo "<p>Usuario no encontrado.</p>";
    exit();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $rol = intval($_POST['rol']);

    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, rol = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $rol, $id);

    if ($stmt->execute()) {
        $mensaje = "✅ Usuario actualizado correctamente.";
        // Actualizar los datos mostrados
        $usuario['nombre'] = $nombre;
        $usuario['apellido'] = $apellido;
        $usuario['correo'] = $correo;
        $usuario['rol'] = $rol;
    } else {
        $mensaje = "❌ Error al actualizar usuario: " . $conn->error;
    }

    $stmt->close();
}

// Obtener lista de roles
$roles = $conn->query("SELECT id, nombre FROM roles");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - Global Transportes</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<header>
    <div class="contenedor">
        <div class="logo">
            <a href="../index.html"><img src="../img/logo.png" alt="Logo Global Transportes" class="logo-img"></a>
        </div>
        <h1>Editar Usuario</h1>
    </div>
</header>

<main class="contenido">
    <div class="formulario">
        <?php if ($mensaje): ?>
            <p style="color: <?= strpos($mensaje, '✅') === 0 ? 'green' : 'red' ?>; font-weight: bold;">
                <?= htmlspecialchars($mensaje) ?>
            </p>
        <?php endif; ?>

        <form method="post">
            <label>Nombre:
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </label>

            <label>Apellido:
                <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
            </label>

            <label>Correo:
                <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
            </label>

            <label>Rol:
                <select name="rol" required>
                    <?php while ($row = $roles->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>" <?= $usuario['rol'] == $row['id'] ? 'selected' : '' ?>>
                            <?= ucfirst(htmlspecialchars($row['nombre'])) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label>

            <div class="acciones">
                <button type="submit" class="boton-accion">Actualizar</button>
                <a href="usuarios.php" class="boton-accion">Cancelar</a>
                <a href="panel_admin.php" class="boton-accion">Volver al Panel</a>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2025 Global Transportes. Todos los derechos reservados.</p>
</footer>
</body>
</html>
