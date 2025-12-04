<?php
// modulos/conductores/views/profile.php
session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../includes/config.php';
// require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();



// Permisos: solo admin
if (!isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../../sistema/login.php");
    exit;
}

// Obtener ID de conductor
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo "ID de conductor inválido";
    exit;
}

// 1) Datos del conductor
$stmt = $conn->prepare("
    SELECT id, dni, nombres, apellidos,
           licencia_conducir, telefono, correo, activo
    FROM conductores
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$conductor = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 2) Documentos vigentes del conductor
$stmt = $conn->prepare("
    SELECT d.id, td.nombre AS tipo_documento,
           d.numero, d.archivo,
           DATE_FORMAT(d.fecha_inicio, '%Y-%m-%d') AS fecha_inicio,
           DATE_FORMAT(d.fecha_vencimiento, '%Y-%m-%d') AS fecha_vencimiento
    FROM documentos d
    JOIN tipos_documento td ON d.tipo_documento_id = td.id
    WHERE d.entidad_tipo = 'conductor' AND d.entidad_id = ?
    ORDER BY d.fecha_vencimiento DESC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$documentos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 3) Historial de asignaciones
$stmt = $conn->prepare("
    SELECT ac.id,
           v.placa AS vehiculo,
           o.numero_orden AS orden,
           DATE_FORMAT(ac.fecha_asignacion, '%Y-%m-%d') AS fecha_asignacion,
           DATE_FORMAT(ac.fecha_finalizacion, '%Y-%m-%d') AS fecha_finalizacion
    FROM asignaciones_conductor ac
    LEFT JOIN vehiculos v ON ac.vehiculo_id = v.id
    LEFT JOIN ordenes_vehiculo ov ON ac.orden_vehiculo_id = ov.id
    LEFT JOIN ordenes_trabajo o ON ov.orden_trabajo_id = o.id
    WHERE ac.conductor_id = ?
    ORDER BY ac.fecha_asignacion DESC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$asignaciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<div class="container py-4">
  <a href="../conductores.php" class="btn btn-outline-secondary mb-3">
    ← Volver a Conductores
  </a>

  <h2 class="mb-4">👤 Perfil del Conductor</h2>

  <div class="row mb-5">
    <div class="col-md-6">
      <h5>Datos Personales</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Nombre:</strong>
          <?= htmlspecialchars($conductor['apellidos'] . ', ' . $conductor['nombres']) ?>
        </li>
        <li class="list-group-item"><strong>DNI:</strong>
          <?= htmlspecialchars($conductor['dni']) ?>
        </li>
        <li class="list-group-item"><strong>Licencia:</strong>
          <?= htmlspecialchars($conductor['licencia_conducir']) ?>
        </li>
        <li class="list-group-item"><strong>Teléfono:</strong>
          <?= htmlspecialchars($conductor['telefono']) ?>
        </li>
        <li class="list-group-item"><strong>Correo:</strong>
          <?= htmlspecialchars($conductor['correo']) ?>
        </li>
        <li class="list-group-item">
          <strong>Estado:</strong>
          <?php if ($conductor['activo']): ?>
            <span class="badge bg-success">Activo</span>
          <?php else: ?>
            <span class="badge bg-danger">Inactivo</span>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>

  <h5 class="mb-3">📄 Documentos Vigentes</h5>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Tipo</th>
        <th>Número</th>
        <th>Inicio</th>
        <th>Vencimiento</th>
        <th>Archivo</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($documentos)): ?>
        <tr>
          <td colspan="6" class="text-center">No hay documentos registrados.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($documentos as $doc): ?>
          <tr>
            <td><?= $doc['id'] ?></td>
            <td><?= htmlspecialchars($doc['tipo_documento']) ?></td>
            <td><?= htmlspecialchars($doc['numero']) ?></td>
            <td><?= $doc['fecha_inicio'] ?></td>
            <td><?= $doc['fecha_vencimiento'] ?></td>
            <td>
              <?php if ($doc['archivo']): ?>
                <a href="../../../uploads/<?= htmlspecialchars($doc['archivo']) ?>"
                   target="_blank">Ver</a>
              <?php else: ?>
                —
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <h5 class="mt-5 mb-3">🚚 Historial de Asignaciones</h5>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Vehículo (Placa)</th>
        <th>Orden N°</th>
        <th>Asignado</th>
        <th>Finalizado</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($asignaciones)): ?>
        <tr>
          <td colspan="5" class="text-center">Sin historial de asignaciones.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($asignaciones as $asig): ?>
          <tr>
            <td><?= $asig['id'] ?></td>
            <td><?= htmlspecialchars($asig['vehiculo'] ?? '—') ?></td>
            <td><?= htmlspecialchars($asig['orden'] ?? '—') ?></td>
            <td><?= $asig['fecha_asignacion'] ?></td>
            <td><?= $asig['fecha_finalizacion'] ?: '—' ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
