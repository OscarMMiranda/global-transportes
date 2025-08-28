<?php
// modulos/vehiculos/acciones/eliminar.php
// Este script procesa el soft-delete de un vehículo

// 1) Cargar configuración, utilidades y modelo
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
require_once __DIR__   . '/../includes/funciones.php';
require_once __DIR__   . '/../modelo.php';

// 2) Inicializar conexión y sesión
$conn = getConnection();
validarSesionAdmin();

// 3) Preparar respuesta
$response = [
    'success' => false,
    'message' => 'Petición inválida.'
];

// 4) Validar método y entrada, luego eliminar (soft-delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && isset($_POST['id']) 
    && validarId($_POST['id'])
) {
    $id        = (int) $_POST['id'];
    $usuarioId = $_SESSION['usuario_id'];
    $ipOrigen  = obtenerIP();

    if (eliminarVehiculo($conn, $id, $usuarioId, $ipOrigen)) {
        // Registrar en historial propio de vehículos
        registrarVehiculoHistorial($conn, $id, $usuarioId, 'Eliminado');
        // Registrar en historial global del ERP
        registrarEnHistorial(
            $_SESSION['usuario'],
            "Eliminó vehículo ID {$id}",
            'vehiculos',
            $ipOrigen
        );

        $response['success'] = true;
        $response['message'] = 'Vehículo eliminado correctamente.';
    }
    else {
        $response['message'] = 'Error al eliminar el vehículo.';
    }
}

// 5) Detectar AJAX y devolver JSON o redirigir
$isAjax = ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
          && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// petición normal: redirigir con parámetros de estado
$query = $response['success']
       ? 'action=list&msg=eliminado'
       : 'action=list&error=' . urlencode($response['message']);

header("Location: index.php?{$query}");
exit;