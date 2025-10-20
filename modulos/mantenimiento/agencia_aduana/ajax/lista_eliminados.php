<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

register_shutdown_function(function() {
  $error = error_get_last();
  if ($error) {
    echo '<div class="alert alert-danger text-center">❌ Error fatal: ' . $error['message'] . '</div>';
    error_log("❌ Error fatal: " . $error['message']);
  }
});

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
require_once __DIR__ . '/../modelo/AgenciaModel.php';

$conexion = getConnection();
if (!$conexion || !$conexion instanceof mysqli) {
  echo '<div class="alert alert-danger text-center">❌ Conexión inválida.</div>';
  error_log("❌ Conexión inválida o no es mysqli.");
  return;
}

$modelo = new AgenciaModel($conexion);
$agencias = $modelo->listarEliminadas();

// if (!is_array($agencias)) {
//   echo '<div class="alert alert-danger text-center">❌ Error al obtener agencias eliminadas.</div>';
//   error_log("❌ El modelo no devolvió un array válido.");
//   return;
// }

if (empty($agencias)) {
  echo '<div class="alert alert-info text-center">No hay agencias eliminadas registradas.</div>';
  error_log("ℹ️ No se encontraron agencias eliminadas.");
  return;
}

echo '<table id="tablaInactivos" class="table table-bordered table-hover table-sm">
  <thead class="table-dark">
    <tr>
      <th>Nombre</th>
      <th>RUC</th>
      <th>Dirección</th>
      <th>Creación</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>';

foreach ($agencias as $agencia) {
  echo '<tr>
    <td>' . htmlspecialchars(isset($agencia['nombre']) ? $agencia['nombre'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['ruc']) ? $agencia['ruc'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['direccion']) ? $agencia['direccion'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['fecha_creacion']) ? $agencia['fecha_creacion'] : '—') . '</td>
    <td>
      <button class="btn btn-sm btn-success" onclick="reactivarAgencia(' . $agencia['id'] . ')">
        <i class="fas fa-undo-alt"></i>
      </button>
    </td>
  </tr>';
}

echo '</tbody></table>';