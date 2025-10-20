<?php
// archivo: /admin/users/ajax/tabla_activos.php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);

define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));
require_once ROOT_PATH . '/includes/config.php';

$conn = getConnection();

$sql = "
  SELECT 
    u.id,
    u.nombre,
    u.apellido,
    u.usuario,
    u.correo,
    r.nombre AS rol,
    u.creado_en
  FROM usuarios u
  JOIN roles r ON u.rol = r.id
  WHERE u.eliminado = 0
  ORDER BY u.id ASC
";

$result = $conn->query($sql);

// Renderizar tabla completa
echo '<div class="table-responsive">';
echo '<table id="tablaEliminados" class="table table-bordered table-striped">';
echo '<thead class="table-secondary">';
echo '<tr>';
echo '<th>ID</th>';
echo '<th>Nombres</th>';
echo '<th>Apellidos</th>';
echo '<th>Usuario</th>';
echo '<th>Correo</th>';
echo '<th>Rol</th>';
echo '<th>Fecha creado</th>';
echo '<th class="text-center">Acciones</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';



if (!$result || $result->num_rows === 0) {
  echo '<tr><td colspan="8" class="text-muted text-center">No hay usuarios activos.</td></tr>';
  exit;
}

while ($u = $result->fetch_assoc()) {
  $id       = (int)$u['id'];
  $nombre   = htmlspecialchars($u['nombre']);
  $apellido = htmlspecialchars($u['apellido']);
  $usuario  = htmlspecialchars($u['usuario']);
  $correo   = htmlspecialchars($u['correo']);
  $rol      = htmlspecialchars(ucfirst($u['rol']));
  $fecha    = htmlspecialchars($u['creado_en']);

  echo '<tr data-id="' . $id . '">';
  echo '<td>' . $id . '</td>';
  echo '<td class="nombre">' . $nombre . '</td>';
  echo '<td class="apellido">' . $apellido . '</td>';
  echo '<td>' . $usuario . '</td>';
  echo '<td class="correo">' . $correo . '</td>';
  echo '<td class="rol">' . $rol . '</td>';
  echo '<td>' . $fecha . '</td>';
  echo '<td class="text-center">';

  // Botón editar
  echo '<button class="btn btn-outline-primary btn-sm me-1 btn-editar" data-id="' . $id . '" title="Editar">';
  echo '<i class="fas fa-pencil-alt"></i></button>';

  // Botón eliminar
  echo '<button class="btn btn-outline-danger btn-sm me-1 btn-eliminar" data-id="' . $id . '" title="Eliminar">';
  echo '<i class="fas fa-trash-alt"></i></button>';

  // Botón ver (opcional)
  echo '<button class="btn btn-outline-secondary btn-sm btn-ver" data-id="' . $id . '" title="Ver detalles">';
  echo '<i class="fas fa-eye"></i></button>';

  echo '</td>';
  echo '</tr>';
}