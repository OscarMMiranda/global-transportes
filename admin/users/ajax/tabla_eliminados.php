<?php
// archivo: /admin/users/ajax/tabla_eliminados.php

// Activar depuración
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);

// Ruta blindada compatible con PHP 5.6
define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));
require_once ROOT_PATH . '/includes/config.php';

$conn = getConnection();

// Consulta trazable de usuarios eliminados
$sql = "
  SELECT 
    u.id,
    u.usuario,
    u.correo,
    r.nombre AS rol,
    u.eliminado_por,
    u.eliminado_en
  FROM usuarios u
  JOIN roles r ON u.rol = r.id
  WHERE u.eliminado = 1
  ORDER BY u.eliminado_en DESC
";

$result = $conn->query($sql);

// Renderizar tabla completa
echo '<div class="table-responsive">';
echo '<table id="tablaEliminados" class="table table-bordered table-striped">';
echo '<thead class="table-secondary">';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Usuario</th>';
echo '<th>Correo</th>';
echo '<th>Rol</th>';
echo '<th>Eliminado por</th>';
echo '<th>Fecha de eliminación</th>';
echo '<th class="text-center">Acciones</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (!$result || $result->num_rows === 0) {
  echo '<tr><td colspan="7" class="text-muted text-center">No hay usuarios eliminados.</td></tr>';
} else {
  while ($u = $result->fetch_assoc()) {
    $id             = (int)$u['id'];
    $usuario        = htmlspecialchars($u['usuario']);
    $correo         = htmlspecialchars($u['correo']);
    $rol            = htmlspecialchars(ucfirst($u['rol']));
    $eliminado_por  = (int)$u['eliminado_por'];
    $fecha          = htmlspecialchars($u['eliminado_en']);

    echo '<tr data-id="' . $id . '">';
    echo '<td>' . $id . '</td>';
    echo '<td>' . $usuario . '</td>';
    echo '<td>' . $correo . '</td>';
    echo '<td>' . $rol . '</td>';
    echo '<td>' . ($eliminado_por ?: '—') . '</td>';
    echo '<td>' . ($fecha ?: '—') . '</td>';
    echo '<td class="text-center">';

    // Botón restaurar
    echo '<button class="btn btn-outline-success btn-sm me-1 btn-restaurar" data-id="' . $id . '" title="Restaurar">';
    echo '<i class="fas fa-undo"></i></button>';

    // Botón ver (opcional)
    echo '<button class="btn btn-outline-secondary btn-sm me-1 btn-ver" data-id="' . $id . '" title="Ver detalles">';
    echo '<i class="fas fa-eye"></i></button>';

    // Botón eliminar definitivo (opcional)
    echo '<button class="btn btn-outline-danger btn-sm btn-eliminar-def" data-id="' . $id . '" title="Eliminar definitivamente">';
    echo '<i class="fas fa-trash-alt"></i></button>';

    echo '</td>';
    echo '</tr>';
  }
}

echo '</tbody>';
echo '</table>';
echo '</div>';