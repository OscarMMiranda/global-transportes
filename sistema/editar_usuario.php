<?php
session_start();
require_once '../includes/conexion.php';

// Activar modo depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Verificar acceso solo para administradores
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    error_log("❌ Acceso no autorizado: " . $_SERVER['REMOTE_ADDR']);
    header("Location: login.php");
    exit();
}

// Validar ID de usuario
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("❌ Error: ID de usuario no válido.");
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT id, nombre, apellido, correo, rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

if (!$usuario) {
    die("❌ Error: Usuario no encontrado.");
}

// Procesar actualización
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $rol = intval($_POST['rol']);

    // Ejecutar actualización con validación de errores
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, rol = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $rol, $id);

    if ($stmt->execute()) {
        $mensaje = "✅ Usuario actualizado correctamente.";

        // Guardar registro en historial_bd
        $admin_usuario = $_SESSION['usuario'];
        $accion = "Modificó usuario ID $id";
        $ip_usuario = $_SERVER['REMOTE_ADDR'];
        $sql_historial = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario) VALUES ('$admin_usuario', '$accion', 'usuarios', '$ip_usuario')";
        $conn->query($sql_historial);

        // Actualizar variables
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

        <form method="post" onsubmit="return confirmarActualizacion();">
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

<script>
function confirmarActualizacion() {
    return confirm("⚠️ ¿Confirmar la actualización de este usuario?");
}
</script>

</body>
</html>
