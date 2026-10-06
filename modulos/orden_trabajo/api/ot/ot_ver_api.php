<?php
// ======================================================
// API: ot_ver_api.php
// MÓDULO: Órdenes de Trabajo (OT)
// RESPONSABILIDAD: Obtener datos + vista HTML + viajes
// GLOBAL 2026
// ======================================================

// ------------------------------
// Validación de sesión
// ------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "Sesión no válida"
    ));

    exit;
}

// ------------------------------
// Configuración global
// ------------------------------
require_once __DIR__ . '/../../../../includes/config.php';

$conn = getConnection();

if (!$conn) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "Error de conexión con la base de datos"
    ));

    exit;
}

// ------------------------------
// Modelos
// ------------------------------
require_once __DIR__ . '/../../models/OrdenTrabajoModel.php';
require_once __DIR__ . '/../../models/OrdenVehiculoModel.php';
require_once __DIR__ . '/../../models/ViajesModel.php';

$modelOT  = new OrdenTrabajoModel($conn);
$modelOV  = new OrdenVehiculoModel($conn);
$modelVia = new ViajesModel($conn);

// ------------------------------
// ID
// ------------------------------
$id = isset($_POST['id'])
    ? intval($_POST['id'])
    : 0;

if ($id <= 0) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "ID de OT no válido"
    ));

    exit;
}

// ------------------------------
// OT
// ------------------------------
$data = $modelOT->getOTById($id);

if (!$data) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "No se encontró la OT"
    ));

    exit;
}

// ------------------------------
// OV
// ------------------------------
$ordenVehiculo =
    $modelOV->getOrdenVehiculoPorOT($id);

// ------------------------------
// VIAJES
// ------------------------------
$viajes = array();

if ($ordenVehiculo) {

    $viajes =
        $modelVia->getViajesPorOrdenVehiculo(
            $ordenVehiculo['id']
        );
}

// ------------------------------
// VIEW
// ------------------------------
ob_start();

include __DIR__ . '/../../views/ver_ot.php';

$html = ob_get_clean();

// ------------------------------
// RESPUESTA
// ------------------------------
echo json_encode(array(

    "ok"     => true,

    "html"   => $html,

    "viajes" => $viajes

));

exit;