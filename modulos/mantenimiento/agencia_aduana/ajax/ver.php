<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
require_once __DIR__ . '/../modelo/AgenciaModel.php';



$conn = getConnection();
if (!$conn || !$conn instanceof mysqli) {
  echo '<div class="alert alert-danger text-center">❌ Error de conexión.</div>';
  error_log("❌ Conexión inválida en ver.php");
  return;
}

$modelo = new AgenciaModel($conn);

// 🔍 Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
  echo '<div class="alert alert-danger text-center">❌ ID inválido.</div>';
  error_log("❌ ID inválido recibido en ver.php");
  return;
}

$agencia = $modelo->obtenerPorId($id);
if (!is_array($agencia)) {
  echo '<div class="alert alert-warning text-center">⚠️ Agencia no encontrada.</div>';
  error_log("⚠️ No se encontró la agencia con ID $id");
  return;
}

// 🎨 Ficha visual
echo '<div class="table-responsive">
  	<table class="table table-bordered table-sm">
    	<tbody>
      		<tr><th>ID</th><td>' 		. $agencia['id'] . '</td></tr>
      		<tr><th>Nombre</th><td>' 	. htmlspecialchars($agencia['nombre']) . '</td></tr>
      		<tr><th>RUC</th><td>'     	. htmlspecialchars($agencia['ruc']) . '</td></tr>
      		<tr><th>Dirección</th><td>' . htmlspecialchars($agencia['direccion']) . '</td></tr>
      		<tr><th>Distrito</th><td>' 	. htmlspecialchars($agencia['distrito_nombre']) . '</td></tr>
      		<tr><th>Provincia</th><td>' . htmlspecialchars($agencia['provincia_nombre']) . '</td></tr>
      		<tr><th>Departamento</th><td>' . htmlspecialchars($agencia['departamento_nombre']) . '</td></tr>
      		<tr><th>Fecha de creación</th><td>' . htmlspecialchars($agencia['fecha_creacion']) . '</td></tr>
      		<tr><th>Estado</th><td>' . ($agencia['estado'] == 1 ? '🟢 Activo' : '🔴 Inactivo') . '</td></tr>
    	</tbody>
  	</table>
</div>';

