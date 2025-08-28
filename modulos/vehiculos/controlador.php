<?php
// controlador.php — Lógica principal del módulo Vehículos

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__ . '/modelo.php';
require_once __DIR__ . '/includes/funciones.php';

$conn = getConnection();
validarSesionAdmin();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$error  = '';

// Enrutador principal
switch ($action) {
  case 'list':
    // Listado de vehículos activos e inactivos
    $vehiculos_activos   = obtenerVehiculos($conn, true);
    $vehiculos_inactivos = obtenerVehiculos($conn, false);
    include __DIR__ . '/vistas/listado.php';
    break;

  case 'create':
    // Formulario vacío para crear
    include __DIR__ . '/vistas/formulario.php';
    break;

  case 'store':
    // Guardar nuevo vehículo
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $placa         = trim($_POST['placa']);
      $modelo        = trim($_POST['modelo']);
      $anio          = intval($_POST['anio']);
      $tipo_id       = intval($_POST['tipo_id']);
      $marca_id      = intval($_POST['marca_id']);
      $empresa_id    = intval($_POST['empresa_id']);
      $observaciones = trim($_POST['observaciones']);
      $usuarioId     = obtenerUsuarioId();
      $ipOrigen      = obtenerIP();

      // Validación básica
      if ($placa && $modelo && $anio && $tipo_id && $marca_id && $empresa_id) {
        // Inserción en BD
        $sql = "
          INSERT INTO vehiculos 
            (placa, modelo, anio, tipo_id, marca_id, empresa_id, observaciones,
             estado_id, fecha_creado, creado_por, ip_origen)
          VALUES
            (?, ?, ?, ?, ?, ?, ?,
             (SELECT id FROM estado_vehiculo WHERE nombre = 'activo'),
             NOW(), ?, ?)
        ";
        $stmt = $conn->prepare($sql);
        if ($stmt && $stmt->bind_param(
          'ssiiissis',
          $placa, $modelo, $anio,
          $tipo_id, $marca_id, $empresa_id,
          $observaciones,
          $usuarioId, $ipOrigen
        )) {
          if ($stmt->execute()) {
            $nuevoId = $conn->insert_id;
            registrarVehiculoHistorial($conn, $nuevoId, $usuarioId, 'creado');
            header('Location: index.php');
            exit;
          } else {
            error_log("[vehiculos] ERROR execute store: " . $stmt->error);
            $error = "No se pudo guardar el vehículo.";
          }
        } else {
          error_log("[vehiculos] ERROR prepare store: " . $conn->error);
          $error = "Error al preparar el guardado.";
        }
      } else {
        $error = "Faltan campos obligatorios.";
      }
    }
    // Si hay error o no es POST, recargamos el formulario
    include __DIR__ . '/vistas/formulario.php';
    break;

  case 'edit':
    // Mostrar formulario con datos existentes
    $id       = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $vehiculo = getVehiculoPorId($conn, $id);
    if ($vehiculo) {
      include __DIR__ . '/vistas/formulario_editar.php';
    } else {
      echo "<div class='alert alert-danger'>Vehículo no encontrado. ID: $id</div>";
    }
    break;

  case 'update':
    // Procesar edición de vehículo
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id            = isset($_GET['id']) ? intval($_GET['id']) : 0;
      $placa         = trim($_POST['placa']);
      $modelo        = trim($_POST['modelo']);
      $anio          = intval($_POST['anio']);
      $tipo_id       = intval($_POST['tipo_id']);
      $marca_id      = intval($_POST['marca_id']);
      $empresa_id    = intval($_POST['empresa_id']);
      $observaciones = trim($_POST['observaciones']);
      $usuarioId     = obtenerUsuarioId();
      $ipOrigen      = obtenerIP();

      if ($id > 0 && $placa && $modelo && $anio && $tipo_id && $marca_id && $empresa_id) {
        $ok = actualizarVehiculo(
          $conn,
          $id,
          $placa,
          $modelo,
          $anio,
          $tipo_id,
          $marca_id,
          $empresa_id,
          $observaciones,
          $usuarioId,
          $ipOrigen
        );
        if ($ok) {
          registrarVehiculoHistorial($conn, $id, $usuarioId, 'actualizado');
          header('Location: index.php');
          exit;
        } else {
          error_log("[vehiculos] ERROR execute update para ID $id");
          $error = "No se pudo actualizar el vehículo.";
        }
      } else {
        $error = "Faltan datos o ID inválido.";
      }
    }
    // Siempre recargamos el formulario de edición (con $error si aplica)
    $id       = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $vehiculo = getVehiculoPorId($conn, $id);
    include __DIR__ . '/vistas/formulario_editar.php';
    break;

  case 'delete':
    // Acción de borrado lógico
    require_once __DIR__ . '/acciones/eliminar.php';
    break;

  case 'restore':
    // Acción de restauración
    require_once __DIR__ . '/acciones/restaurar.php';
    break;

  default:
    http_response_code(404);
    echo "<div class='container mt-5'>
            <h3 class='text-danger'>Acción no reconocida: <code>{$action}</code></h3>
          </div>";
    break;
}