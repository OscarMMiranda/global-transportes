<?php
	// archivo: /modulos/mantenimiento/tipo_vehiculo/ajax/form_view_loader.php

	// 1) Activar modo depuración solo en desarrollo
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', __DIR__ . '/../error_log.txt');

	// 2) Cargar dependencias
	require_once __DIR__ . '/../modelo/TipoVehiculoModel.php';
	require_once __DIR__ . '/../../../../includes/config.php';

	// 3) Conexión a la base de datos
	$conn = getConnection();
	$modelo = new TipoVehiculoModel($conn);

	// 4) Validar ID recibido
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	if ($id <= 0) {
  		echo '<div class="alert alert-warning text-center">⚠️ ID inválido.</div>';
  		exit;
		}

// 5) Obtener vehículo
$vehiculo = $modelo->obtenerPorId($id);
if (!$vehiculo) {
  echo '<div class="alert alert-warning text-center">⚠️ Vehículo no encontrado.</div>';
  exit;
}

// 6) Renderizar detalles
?>
<div class="mb-3">
  <label class="form-label fw-bold">Nombre:</label>
  <div class="form-control"><?= htmlspecialchars($vehiculo['nombre'], ENT_QUOTES, 'UTF-8') ?></div>
</div>

<div class="mb-3">
  <label class="form-label fw-bold">Categoría:</label>
  <div class="form-control"><?= htmlspecialchars($vehiculo['categoria_nombre'], ENT_QUOTES, 'UTF-8') ?></div>
</div>

<div class="mb-3">
  <label class="form-label fw-bold">Descripción:</label>
  <div class="form-control"><?= htmlspecialchars($vehiculo['descripcion'], ENT_QUOTES, 'UTF-8') ?></div>
</div>

<div class="mb-3">
  <label class="form-label fw-bold">Última modificación:</label>
  <div class="form-control"><?= htmlspecialchars($vehiculo['fecha_modificacion'], ENT_QUOTES, 'UTF-8') ?></div>
</div>

<?php
// 7) Cerrar conexión
$conn->close();
?>