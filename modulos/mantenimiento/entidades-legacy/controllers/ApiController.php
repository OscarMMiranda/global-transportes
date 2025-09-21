<?php
    // archivo  :   /modulos/mantenimiento/entidades/controllers/ApiController.php

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// Validación defensiva
if (!$conn) {
  echo json_encode(['estado' => 'error', 'mensaje' => 'Sin conexión']);
  exit;
}

// Enrutamiento por operación
$op = isset($_GET['op']) ? $_GET['op'] : '';

switch ($op) {

  case 'buscarPorRUC':
    $ruc = isset($_GET['ruc']) ? trim($_GET['ruc']) : '';
    if ($ruc === '') {
      echo json_encode(['estado' => 'error', 'mensaje' => 'RUC vacío']);
      exit;
    }

    $stmt = $conn->prepare("SELECT id, nombre, direccion FROM entidades WHERE ruc = ? AND estado = 'activo'");
    $stmt->bind_param("s", $ruc);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();

    echo json_encode([
      'estado' => $data ? 'ok' : 'no_encontrado',
      'entidad' => $data ?: null
    ]);
    break;

  case 'detalle':
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
      echo json_encode(['estado' => 'error', 'mensaje' => 'ID inválido']);
      exit;
    }

    $stmt = $conn->prepare("SELECT * FROM entidades WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();

    echo json_encode([
      'estado' => $data ? 'ok' : 'no_encontrado',
      'entidad' => $data ?: null
    ]);
    break;

  default:
    echo json_encode(['estado' => 'error', 'mensaje' => 'Operación no válida']);
    break;
}

$conn->close();