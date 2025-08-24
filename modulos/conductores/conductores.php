<?php
session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../includes/config.php';

$conn = getConnection();


// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
    exit();
}

// Consultar conductores
$sql = "SELECT id, dni, nombres, apellidos, licencia_conducir, telefono, correo, activo FROM conductores ORDER BY nombres ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Conductores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../../css/conductores.css">
</head>
<body>

<div class="contenedor">
    <h1>Gestión de Conductores</h1>

    <div class="botones">
        <a href="registrar_conductor.php" class="btn">➕ Agregar Conductor</a>
        <a href="../erp_dashboard.php" class="btn">⬅️ Volver al Dashboard</a>
    </div>

    <table class="tabla-conductores">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Licencia</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($conductor = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($conductor['apellidos'] . ', ' . $conductor['nombres']) ?></td>
                    <td><?= htmlspecialchars($conductor['dni']) ?></td>
                    <td><?= htmlspecialchars($conductor['licencia_conducir']) ?></td>
                    <td><?= htmlspecialchars($conductor['telefono']) ?></td>
                    <td><?= htmlspecialchars($conductor['correo']) ?></td>
                    <td><?= $conductor['activo'] ? "✅ Activo" : "❌ Inactivo" ?></td>
                    <td>
                        <a href="ver_conductor.php?id=<?= $conductor['id'] ?>" class="btn-ver">👁️ Ver</a>
                        <a href="editar_conductor.php?id=<?= $conductor['id'] ?>" class="btn-editar">✏️ Editar</a>
                        <a href="eliminar_conductor.php?id=<?= $conductor['id'] ?>" class="btn-eliminar">🗑️ Dar de Baja</a>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php $conn->close(); ?> <!-- Cerrar la conexión correctamente -->

</body>
</html>
