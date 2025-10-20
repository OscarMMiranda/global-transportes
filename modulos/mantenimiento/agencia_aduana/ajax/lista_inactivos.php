<?php
// archivo: /modulos/mantenimiento/agencia_aduana/ajax/lista_inactivos.php

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


// 🔍 Diagnóstico visual temporal
// echo '<pre style="background:#f9f9f9;border:1px solid #ccc;padding:10px;">';
// print_r($agencias);
// echo '</pre>';

if (!is_array($agencias)) {
  echo '<div class="alert alert-danger text-center">❌ Error al obtener agencias eliminadas.</div>';
  error_log("❌ El modelo no devolvió un array válido.");
  return;
}

if (empty($agencias)) {
  echo '<div class="alert alert-info text-center">ℹ️ No hay agencias eliminadas registradas.</div>';
  error_log("ℹ️ No se encontraron agencias eliminadas.");
  return;
}

echo '<table class="table table-bordered table-hover table-sm">
  <thead class="table-light">
    <tr>
      <th>Nombre</th>
      <th>RUC</th>
      <th>Dirección</th>
      <th>Distrito</th>
      <th>Provincia</th>
      <th>Departamento</th>
      <th>Creación</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>';

foreach ($agencias as $agencia) {
  echo '<tr class="table-danger">
    <td>' . htmlspecialchars(isset($agencia['nombre']) ? $agencia['nombre'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['ruc']) ? $agencia['ruc'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['direccion']) ? $agencia['direccion'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['distrito_nombre']) ? $agencia['distrito_nombre'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['provincia_nombre']) ? $agencia['provincia_nombre'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['departamento_nombre']) ? $agencia['departamento_nombre'] : '—') . '</td>
    <td>' . htmlspecialchars(isset($agencia['fecha_creacion']) ? $agencia['fecha_creacion'] : '—') . '</td>
    <td><span class="badge bg-secondary">Eliminado</span></td>
    <td>
      <button class="btn btn-sm btn-success" onclick="reactivarAgencia(' . $agencia['id'] . ')">
        ⟳
      </button>
    </td>
  </tr>';
}

echo '</tbody></table>';

$conexion->close();