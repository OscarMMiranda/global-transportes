<?php
// archivo: /modulos/conductores/api.php

require_once '../../includes/config.php';
require_once 'controllers/conductores_controller.php';

header('Content-Type: application/json');

// 🔒 Conexión segura
$conn = getConnection();
if (!$conn || !($conn instanceof mysqli)) {
  error_log('❌ Error de conexión en api.php');
  echo json_encode(['success' => false, 'error' => 'No se pudo conectar a la base de datos']);
  exit;
}

// 🔀 Operación solicitada
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

switch ($op) {
  case 'list':
    try {
      $incluirInactivos = isset($_GET['filter']) && $_GET['filter'] === 'all';
      $conductores = listarConductores($conn, $incluirInactivos);
      echo json_encode(['success' => true, 'data' => $conductores]);
    } catch (Exception $e) {
      error_log('❌ Error en op=list: ' . $e->getMessage());
      echo json_encode(['success' => false, 'error' => 'Error al listar conductores']);
    }
    break;

  case 'get':
    try {
      $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
      if ($id <= 0) throw new Exception('ID inválido');

      $conductor = obtenerConductorPorId($conn, $id);
      if (!$conductor) throw new Exception('Conductor no encontrado');

      echo json_encode(['success' => true, 'data' => $conductor]);
    } catch (Exception $e) {
      error_log('❌ Error en op=get: ' . $e->getMessage());
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    break;

  case 'save':
    try {
      $error = guardarConductor($conn, $_POST, $_FILES);
      echo json_encode([
        'success' => $error === '',
        'error' => $error ?: 'Error al guardar conductor'
      ]);
    } catch (Exception $e) {
      error_log('❌ Error en op=save: ' . $e->getMessage());
      echo json_encode(['success' => false, 'error' => 'Error al guardar conductor']);
    }
    break;

  case 'delete':
    try {
      $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
      if ($id <= 0) throw new Exception('ID inválido');

      $error = eliminarConductor($conn, $id);
      echo json_encode([
        'success' => $error === '',
        'error' => $error ?: 'Error al eliminar conductor'
      ]);
    } catch (Exception $e) {
      error_log('❌ Error en op=delete: ' . $e->getMessage());
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    break;

  case 'restore':
    try {
      $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
      if ($id <= 0) throw new Exception('ID inválido');

      $error = restaurarConductor($conn, $id);
      echo json_encode([
        'success' => $error === '',
        'error' => $error ?: 'Error al restaurar conductor'
      ]);
    } catch (Exception $e) {
      error_log('❌ Error en op=restore: ' . $e->getMessage());
      echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    break;

  default:
    echo json_encode(['success' => false, 'error' => 'Operación no válida']);
}