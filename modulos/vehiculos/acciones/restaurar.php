<?php
// modulos/vehiculos/acciones/restaurar.php
// Este script procesa la restauración (soft‐undo) de un vehículo

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

// 4) Validar entrada y ejecutar restauración
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && validarId($_POST['id'])) {
    $id        = (int) $_POST['id'];
    $usuarioId = $_SESSION['usuario_id'];
    $ipOrigen  = obtenerIP();

    if (restaurarVehiculo($conn, $id, $usuarioId, $ipOrigen)) {
        // Registrar en historial propio de vehículos
        registrarVehiculoHistorial($conn, $id, $usuarioId, 'Restaurado');
        // Registrar en historial global del ERP
        registrarEnHistorial(
            $_SESSION['usuario'],
            "Restauró vehículo ID {$id}",
            'vehiculos',
            $ipOrigen
        );

        $response['success'] = true;
        $response['message'] = 'Vehículo restaurado correctamente.';
    }
    else {
        $response['message'] = 'Error al restaurar el vehículo.';
    }
}

// 5) Devolver JSON si es AJAX, o redirigir si es petición normal
$isAjax = ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
          && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// petición clásica: redirigir con parámetros de éxito o error
$query = $response['success']
       ? 'action=list&msg=restaurado'
       : 'action=list&error=' . urlencode($response['message']);

header("Location: index.php?{$query}");
exit;