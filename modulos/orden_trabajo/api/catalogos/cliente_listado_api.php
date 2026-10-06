<?php
// ======================================================
//  API: cliente_listado_api.php
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Listado de Clientes para formularios
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================

// ------------------------------
// Validación de sesión
// ------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(array(
        "ok"   => false,
        "msg"  => "Sesión no válida",
        "data" => array()
    ));
    exit;
}

// ------------------------------
// Cargar configuración global
// ------------------------------
// require_once __DIR__ . '/../../../includes/config.php';

require_once __DIR__ . '/../../../../includes/config.php';

// ------------------------------
// Conexión a la base de datos
// ------------------------------
$conn = getConnection();
if (!$conn) {

    error_log("[ERP-OT] Error de conexión en cliente_listado_api: " . date('Y-m-d H:i:s'));

    echo json_encode(array(
        "ok"   => false,
        "msg"  => "Error de conexión",
        "data" => array()
    ));
    exit;
}

// ------------------------------
// Consulta de clientes activos
// ------------------------------
$sql = "
    SELECT id, nombre
    FROM clientes
    WHERE estado = 'Activo'
      AND deleted_at IS NULL
    ORDER BY nombre ASC
";

$res = $conn->query($sql);

$lista = array();

if ($res && $res->num_rows > 0) {

    while ($row = $res->fetch_assoc()) {
        $lista[] = array(
            "id"     => intval($row['id']),
            "nombre" => $row['nombre']
        );
    }

    echo json_encode(array(
        "ok"   => true,
        "msg"  => "Listado obtenido correctamente",
        "data" => $lista
    ));
    exit;
}

// ------------------------------
// Sin resultados
// ------------------------------
echo json_encode(array(
    "ok"   => true,
    "msg"  => "No hay clientes registrados",
    "data" => array()
));
exit;
