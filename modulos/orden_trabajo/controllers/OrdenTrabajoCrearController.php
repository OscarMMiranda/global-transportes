<?php
// ======================================================
//  CONTROLADOR: OrdenTrabajoCrearController.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Crear nueva OT
//  GLOBAL 2026 — Arquitectura Limpia (PHP 5.6)
// ======================================================

// ------------------------------------------------------
// Sesión
// ------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// ------------------------------------------------------
// Configuración
// ------------------------------------------------------
require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();

if (!$conn) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "Error de conexión con la base de datos."
    ));

    exit;
}

// ------------------------------------------------------
// Usuario
// ------------------------------------------------------
$usuario_id = isset($_SESSION['usuario_id'])
    ? intval($_SESSION['usuario_id'])
    : 0;

if ($usuario_id <= 0) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "Sesión no válida."
    ));

    exit;
}

// ------------------------------------------------------
// Mensajes corporativos
// ------------------------------------------------------
$mensajes = array(
    "numero_ot"  => "El número de OT es obligatorio.",
    "fecha"      => "Debe seleccionar una fecha.",
    "semana_ot"  => "La semana OT es obligatoria.",
    "cliente_id" => "Debe seleccionar un cliente.",
    "empresa_id" => "Debe seleccionar una empresa.",
    "tipo_ot_id" => "Debe seleccionar un tipo de OT."
);

// ------------------------------------------------------
// Campos obligatorios
// ------------------------------------------------------
$campos_obligatorios = array(
    "numero_ot",
    "fecha",
    "semana_ot",
    "cliente_id",
    "empresa_id",
    "tipo_ot_id"
);

foreach ($campos_obligatorios as $campo) {

    if (
        !isset($_POST[$campo]) ||
        trim($_POST[$campo]) === ''
    ) {

        echo json_encode(array(
            "ok"  => false,
            "msg" => $mensajes[$campo]
        ));

        exit;
    }
}

// ------------------------------------------------------
// Datos principales
// ------------------------------------------------------
$numero_ot  = trim($_POST['numero_ot']);
$fecha      = trim($_POST['fecha']);
$semana_ot  = trim($_POST['semana_ot']);

$cliente_id = intval($_POST['cliente_id']);
$empresa_id = intval($_POST['empresa_id']);
$tipo_ot_id = intval($_POST['tipo_ot_id']);

// ------------------------------------------------------
// Campos opcionales
// ------------------------------------------------------
$oc_cliente = isset($_POST['oc_cliente'])
    ? trim($_POST['oc_cliente'])
    : '';

$numero_dam = isset($_POST['numero_dam'])
    ? trim($_POST['numero_dam'])
    : '';

$numero_booking = isset($_POST['numero_booking'])
    ? trim($_POST['numero_booking'])
    : '';

$otros = isset($_POST['otros'])
    ? trim($_POST['otros'])
    : '';

// ------------------------------------------------------
// Estado inicial
// ------------------------------------------------------
$estado_id = 1;

// ------------------------------------------------------
// Validar OT duplicada
// ------------------------------------------------------
$sqlDup = "
    SELECT id
    FROM ordenes_trabajo
    WHERE numero_ot = ?
    LIMIT 1
";

$stmtDup = $conn->prepare($sqlDup);

$stmtDup->bind_param(
    "s",
    $numero_ot
);

$stmtDup->execute();

$resDup = $stmtDup->get_result();

if ($resDup && $resDup->num_rows > 0) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => "La OT ya existe."
    ));

    exit;
}

// ------------------------------------------------------
// Auditoría
// ------------------------------------------------------
$ip_origen = isset($_SERVER['REMOTE_ADDR'])
    ? $_SERVER['REMOTE_ADDR']
    : '';

// ------------------------------------------------------
// Insert
// ------------------------------------------------------
$sql = "
INSERT INTO ordenes_trabajo
(
    numero_ot,
    fecha,
    semana_ot,
    cliente_id,
    empresa_id,
    tipo_ot_id,
    estado_id,
    oc_cliente,
    numero_dam,
    numero_booking,
    otros,
    numero_viajes,
    creado_por,
    ip_origen,
    created_at
)
VALUES
(
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
    0,
    ?, ?,
    NOW()
)
";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode(array(
        "ok"  => false,
        "msg" => $conn->error
    ));

    exit;
}

$stmt->bind_param(
    "sssiiiissssis",
    $numero_ot,
    $fecha,
    $semana_ot,
    $cliente_id,
    $empresa_id,
    $tipo_ot_id,
    $estado_id,
    $oc_cliente,
    $numero_dam,
    $numero_booking,
    $otros,
    $usuario_id,
    $ip_origen
);

$ok = $stmt->execute();

// ------------------------------------------------------
// Respuesta
// ------------------------------------------------------
if ($ok) {

    echo json_encode(array(
        "ok" => true,
        "msg" => "Orden de Trabajo creada correctamente.",
        "id" => $conn->insert_id
    ));

} else {

    echo json_encode(array(
        "ok" => false,
        "msg" => "Error al crear OT: " . $stmt->error
    ));
}

$stmt->close();
$conn->close();

exit;