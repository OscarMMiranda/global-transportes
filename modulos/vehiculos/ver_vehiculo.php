<?php
session_start();

// 2) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 3) Cargar config.php (define getConnection() y rutas)
require_once __DIR__ . '/../../includes/config.php';

// 4) Obtener la conexión
$conn = getConnection();

require_once '../../includes/header_erp.php';

// 1) Validar ID por GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['error'] = "ID inválido";
  header("Location: vehiculos.php");
  exit;
}
$id = (int) $_GET['id'];

// 2) Consultar datos del vehículo (incluye joins si quieres nombres)
$sql = "
  SELECT 
    v.id, v.placa, v.modelo, v.anio, v.observaciones,
    m.nombre    AS marca,
    t.nombre    AS tipo,
    e.razon_social AS empresa,
    ev.nombre   AS estado_operativo
  FROM vehiculos v
  JOIN marca_vehiculo   m  ON v.marca_id  = m.id
  JOIN tipo_vehiculo    t  ON v.tipo_id   = t.id
  JOIN empresa          e  ON v.empresa_id= e.id
  JOIN estado_vehiculo  ev ON v.estado_id = ev.id
  WHERE v.id = ? 
    AND v.activo = 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  $_SESSION['error'] = "Vehículo no encontrado";
  header("Location: vehiculos.php");
  exit;
}
$veh = $result->fetch_assoc();
?>

<div class="container mt-4">
  <h2 class="mb-4">Detalles del Vehículo</h2>
  <dl class="row">
    <dt class="col-sm-3">ID</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['id']) ?></dd>

    <dt class="col-sm-3">Placa</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['placa']) ?></dd>

    <dt class="col-sm-3">Marca</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['marca']) ?></dd>

    <dt class="col-sm-3">Tipo</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['tipo']) ?></dd>

    <dt class="col-sm-3">Modelo</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['modelo']) ?></dd>

    <dt class="col-sm-3">Año</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['anio']) ?></dd>

    <dt class="col-sm-3">Empresa</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['empresa']) ?></dd>

    <dt class="col-sm-3">Estado Operativo</dt>
    <dd class="col-sm-9"><?= htmlspecialchars($veh['estado_operativo']) ?></dd>

    <dt class="col-sm-3">Observaciones</dt>
    <dd class="col-sm-9"><?= nl2br(htmlspecialchars($veh['observaciones'])) ?></dd>
  </dl>

  <a href="vehiculos.php" class="btn btn-secondary">← Volver a listado</a>
  <a href="editar_vehiculo.php?id=<?= $veh['id'] ?>"
     class="btn btn-warning">✏️ Editar</a>
</div>

<?php require_once '../../includes/footer_erp.php'; ?>
