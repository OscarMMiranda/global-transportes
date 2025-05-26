<?php
session_start();
require_once '../../includes/conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);



// 🔐 Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') 
    {
    header("Location: ../sistema/login.php");
    exit();
    }


// echo "🔍 estado_id_activo obtenido: " . $estado_id_activo . "<br>";



// 🔎 OBTENER EL ESTADO "ACTIVO"
// $sql_estado_activo = "SELECT id FROM estado_asignacion WHERE nombre = 'activo'";
// $result_estado_activo = $conn->query($sql_estado_activo);

// if (!$result_estado_activo) 
//     {
//     die("❌ Error en consulta estado activo: " . $conn->error);
//     }

// $row_estado_activo = $result_estado_activo->fetch_assoc();
// $estado_id_activo = $row_estado_activo['id'] ?? null;

// if (!$estado_id_activo) 
//     {
//     die("❌ No se encontró el estado 'activo' en estado_asignacion.");
//     } 
// else 
//     {
//     echo "✅ Estado 'activo' encontrado, ID: " . $estado_id_activo . "<br>";
//     }




    
// 🔎 OBTENER EL ESTADO "FINALIZADO"
//   $sql_estado_finalizado = "SELECT id FROM estado_asignacion WHERE nombre = 'finalizado'";
// $result_estado_finalizado = $conn->query($sql_estado_finalizado);

// if (!$result_estado_finalizado) {
//     die("❌ Error en consulta estado finalizado: " . $conn->error);
// }

// $row_estado_finalizado = $result_estado_finalizado->fetch_assoc();
// $estado_id_finalizado = $row_estado_finalizado['id'] ?? null;

// if (!$estado_id_finalizado) {
//     die("❌ No se encontró el estado 'finalizado' en estado_asignacion.");
// } else {
//     echo "✅ Estado 'finalizado' encontrado, ID: " . $estado_id_finalizado . "<br>";
// }




// Obtener asignaciones activas
$sql_activos = "SELECT ac.id, v.placa, v.modelo, c.nombres, c.apellidos, ac.fecha_inicio, es.nombre AS estado
                FROM asignaciones_conductor ac
                JOIN vehiculos v ON ac.vehiculo_id = v.id
                JOIN conductores c ON ac.conductor_id = c.id
                JOIN estado_asignacion es ON ac.estado_id = es.id
                WHERE ac.estado_id = ?
                ORDER BY ac.fecha_inicio DESC";
$stmt_activos = $conn->prepare($sql_activos);
$stmt_activos->bind_param("i", $estado_id_activo);
$stmt_activos->execute();
$result_activos = $stmt_activos->get_result();

// Obtener historial de asignaciones
$sql_historial = "SELECT ac.id, v.placa, v.modelo, c.nombres, c.apellidos, ac.fecha_inicio, COALESCE(ac.fecha_fin, 'En uso') AS fecha_fin, es.nombre AS estado
                  FROM asignaciones_conductor ac
                  JOIN vehiculos v ON ac.vehiculo_id = v.id
                  JOIN conductores c ON ac.conductor_id = c.id
                  JOIN estado_asignacion es ON ac.estado_id = es.id
                  WHERE ac.estado_id = ?
                  ORDER BY ac.fecha_fin DESC";
$stmt_historial = $conn->prepare($sql_historial);
$stmt_historial->bind_param("i", $estado_id_finalizado);
$stmt_historial->execute();
$result_historial = $stmt_historial->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignaciones de Conductores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/base.css">
    <link rel="stylesheet" href="../../css/asignaciones.css">
</head>
<body>

<div class="contenedor">
    <h1>Asignaciones de Conductores</h1>

    <div class="botones">
        <a href="asignar_conductor.php" class="btn">➕ Asignar Vehículo</a>
        <a href="../erp_dashboard.php" class="btn">⬅️ Volver al Dashboard</a>
    </div>

    <!-- Tabla de asignaciones activas -->
    <h2>🚗 Asignaciones Activas</h2>
    <?php if ($result_activos->num_rows > 0) { ?>
        <table class="tabla-asignaciones">
            <thead>
                <tr>
                    <th>Vehículo</th>
                    <th>Modelo</th>
                    <th>Conductor</th>
                    <th>Fecha Asignación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($asignacion = $result_activos->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($asignacion['placa']) ?></td>
                        <td><?= htmlspecialchars($asignacion['modelo']) ?></td>
                        <td><?= htmlspecialchars($asignacion['nombres'] . ' ' . $asignacion['apellidos']) ?></td>
                        <td><?= htmlspecialchars($asignacion['fecha_inicio']) ?></td>
                        <td><?= htmlspecialchars($asignacion['estado']) ?></td>
                        <td>
                            <a href="finalizar_asignacion.php?id=<?= $asignacion['id'] ?>" class="btn-finalizar">🛑 Finalizar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No hay asignaciones activas.</p>
    <?php } ?>

    <!-- Historial de asignaciones -->
    <h2>📜 Historial de Asignaciones</h2>
    <?php if ($result_historial->num_rows > 0) { ?>
        <table class="tabla-historial">
            <thead>
                <tr>
                    <th>Vehículo</th>
                    <th>Modelo</th>
                    <th>Conductor</th>
                    <th>Fecha Asignación</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($historial = $result_historial->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($historial['placa']) ?></td>
                        <td><?= htmlspecialchars($historial['modelo']) ?></td>
                        <td><?= htmlspecialchars($historial['nombres'] . ' ' . $historial['apellidos']) ?></td>
                        <td><?= htmlspecialchars($historial['fecha_inicio']) ?></td>
                        <td><?= htmlspecialchars($historial['fecha_fin']) ?></td>
                        <td><?= htmlspecialchars($historial['estado']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No hay historial de asignaciones.</p>
    <?php } ?>
</div>

<?php 
$stmt_activos->close();
$stmt_historial->close();
$conn->close();
?>
</body>
</html>
