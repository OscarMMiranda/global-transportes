<?php
// ======================================================
//  API: ot_nueva_form_api.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Entregar formulario HTML para Nueva OT
//  GLOBAL 2026 — Arquitectura Limpia
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
// Configuración
// ------------------------------
require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();

if (!$conn) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "Error de conexión"
    ));

    exit;
}

// ------------------------------
// Modelos
// ------------------------------
require_once __DIR__ . '/../models/ClientesModel.php';

$modelClientes = new ClientesModel($conn);

$clientes = $modelClientes->getClientesActivos();

// ======================================================
// OBTENER SIGUIENTE OT
// ======================================================

$anio = date('Y');

$sql = "
    SELECT numero_ot
    FROM ordenes_trabajo
    WHERE numero_ot LIKE '%-$anio'
    ORDER BY numero_ot DESC
    LIMIT 1
";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {

    $row = $res->fetch_assoc();

    $numero_ot = trim($row['numero_ot']);

    list($correlativo, $tmp) = explode('-', $numero_ot);

    $correlativo++;

    $siguiente_ot =
        str_pad($correlativo, 4, '0', STR_PAD_LEFT)
        . '-' .
        $anio;

} else {

    $siguiente_ot = '0001-' . $anio;
}

// ======================================================
// FECHA ACTUAL
// ======================================================

$fecha_hoy = date('Y-m-d');

// ======================================================
// VARIABLES VISTA
// ======================================================

$data = array(
    'clientes'      => $clientes,
    'siguiente_ot'  => $siguiente_ot,
    'fecha_hoy'     => $fecha_hoy
);

// ------------------------------
// Renderizar formulario
// ------------------------------
ob_start();

include __DIR__ . '/../views/ot_nueva_form.php';

$html = ob_get_clean();

// ------------------------------
// Respuesta
// ------------------------------
echo json_encode(array(
    "ok"   => true,
    "html" => $html
));

exit;