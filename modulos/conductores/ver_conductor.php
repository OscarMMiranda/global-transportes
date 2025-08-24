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

    header("Location: http://www.globaltransportes.com/login");

    // header("Location: ../sistema/login.php");
    exit();
}

// Obtener el ID del conductor
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM conductores WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$conductor = $result->fetch_assoc();

if (!$conductor) {
    echo "<p>Conductor no encontrado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Conductor</title>
    <link rel="stylesheet" href="../../css/conductores.css">
    <style>
        .contenedor {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .detalle-info {
            width: 70%;
        }
        .foto-conductor {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <div class="detalle-info">
        <h1>Detalles del Conductor</h1>
        <table class="tabla-detalle">
            <tr><th>Nombre Completo</th><td><?= htmlspecialchars($conductor['nombres'] . ' ' . $conductor['apellidos']) ?></td></tr>
            <tr><th>DNI</th><td><?= htmlspecialchars($conductor['dni']) ?></td></tr>
            <tr><th>Licencia de Conducir</th><td><?= htmlspecialchars($conductor['licencia_conducir']) ?></td></tr>
            <tr><th>Teléfono</th><td><?= htmlspecialchars($conductor['telefono']) ?></td></tr>
            <tr><th>Correo</th><td><?= htmlspecialchars($conductor['correo']) ?></td></tr>
            <tr><th>Estado</th><td><?= $conductor['activo'] ? "✅ Activo" : "❌ Inactivo" ?></td></tr>
        </table>
    </div>

    <!-- Foto del conductor en la esquina superior derecha -->
    <div>
        <?php if (!empty($conductor['foto'])) { ?>
            <img src="<?= htmlspecialchars($conductor['foto']) ?>" alt="Foto del Conductor" class="foto-conductor">
        <?php } else { ?>
            <p>📷 No hay foto disponible.</p>
        <?php } ?>
    </div>
</div>

<div class="botones">
    <a href="editar_conductor.php?id=<?= $conductor['id'] ?>" class="btn">✏️ Editar</a>
    <a href="conductores.php" class="btn">⬅️ Volver a la Lista</a>
</div>

<?php $conn->close(); ?>
</body>
</html>
