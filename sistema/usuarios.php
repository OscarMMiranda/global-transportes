<?php
session_start();
require_once '../includes/conexion.php';

// Activar modo depuración (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
    error_log("❌ Acceso no autorizado: " . $_SERVER['REMOTE_ADDR']);
    header("Location: login.php");
    exit();
}

// Obtener lista de usuarios y roles con verificación de errores
$sql = "SELECT u.id, u.nombre, u.apellido, u.usuario, u.correo, r.nombre AS rol, u.creado_en
        FROM usuarios u
        JOIN roles r ON u.rol = r.id
        ORDER BY u.id ASC";

$resultado = $conn->query($sql);

if (!$resultado) {
    die("<h3>❌ Error al obtener usuarios: " . $conn->error . "</h3>");
}

// **Registrar acceso en historial_bd**
$usuario = $_SESSION['usuario'];
$accion = "Visualizó lista de usuarios";
$ip_usuario = $_SERVER['REMOTE_ADDR'];
$sql_historial = "INSERT INTO historial_bd (usuario, accion, ip_usuario) VALUES ('$usuario', '$accion', '$ip_usuario')";
$conn->query($sql_historial);

// Exportación a CSV
if (isset($_GET['exportar']) && $_GET['exportar'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="usuarios.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ["ID", "Nombre", "Apellido", "Usuario", "Correo", "Rol", "Fecha Creación"]);

    $result_csv = $conn->query($sql);
    while ($fila = $result_csv->fetch_assoc()) {
        fputcsv($output, $fila);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
<div class="container">
    <h1>Lista de Usuarios</h1>
    <a href="crear_usuario.php" class="boton-accion">➕ Crear Usuario</a>
    <a href="usuarios.php?exportar=csv" class="boton-accion">📥 Exportar CSV</a>

    <h3>Buscar Usuario</h3>
    <input type="text" id="buscarUsuario" onkeyup="filtrarUsuarios()" placeholder="🔍 Buscar por nombre o correo...">

    <?php if ($resultado->num_rows > 0): ?>
        <table id="tablaUsuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($fila = $resultado->fetch_assoc()) : ?>
                <tr>
                    <td><?= $fila['id']; ?></td>
                    <td><?= htmlspecialchars($fila['nombre']); ?></td>
                    <td><?= htmlspecialchars($fila['apellido']); ?></td>
                    <td><?= htmlspecialchars($fila['usuario']); ?></td>
                    <td><?= htmlspecialchars($fila['correo']); ?></td>
                    <td><?= htmlspecialchars(ucfirst($fila['rol'])); ?></td>
                    <td><?= htmlspecialchars($fila['creado_en']); ?></td>
                    <td class="acciones">
                        <a href="editar_usuario.php?id=<?= $fila['id']; ?>">✏️ Editar</a> |
                        <a href="eliminar_usuario.php?id=<?= $fila['id']; ?>" onclick="return confirm('⚠️ ¿Eliminar este usuario?');">🗑 Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>📌 No hay usuarios registrados.</p>
    <?php endif; ?>

    <p><a href="panel_admin.php" class="boton-accion">← Volver al Panel</a></p>
</div>

<script>
    function filtrarUsuarios() {
        var input = document.getElementById("buscarUsuario").value.toLowerCase();
        var filas = document.querySelectorAll("tbody tr");

        filas.forEach(fila => {
            fila.style.display = fila.textContent.toLowerCase().includes(input) ? "" : "none";
        });
    }
</script>
</body>
</html>
