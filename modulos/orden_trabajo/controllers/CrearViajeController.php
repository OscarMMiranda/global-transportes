<?php
// ============================================================
// CONTROLADOR: CrearViajeController.php
// RESPONSABILIDAD: Registrar viaje simple asociado a una OT
// ARQUITECTURA LIMPIA 2026 — PHP 5.6 COMPATIBLE
// ============================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

/* ============================================================
   FUNCIÓN: validarInput
   ============================================================ */
function validarInput()
{
    $data = array(
        "ot_id"         => isset($_POST['orden_trabajo_id']) ? intval($_POST['orden_trabajo_id']) : 0,
        "fecha_viaje"   => isset($_POST['fecha_viaje']) ? trim($_POST['fecha_viaje']) : "",
        "semana_viaje"  => isset($_POST['semana_viaje']) ? intval($_POST['semana_viaje']) : 0,
        "origen"        => isset($_POST['origen']) ? trim($_POST['origen']) : "",
        "destino"       => isset($_POST['destino']) ? trim($_POST['destino']) : "",
        "observaciones" => isset($_POST['observaciones']) ? trim($_POST['observaciones']) : ""
    );

    if ($data["ot_id"] <= 0 || $data["fecha_viaje"] === "" || $data["semana_viaje"] <= 0) {
        responder(false, "Datos incompletos para registrar viaje.");
    }

    return $data;
}

/* ============================================================
   FUNCIÓN: validarOT
   ============================================================ */
function validarOT($conn, $ot_id)
{
    $sql = "SELECT id FROM ordenes_trabajo WHERE id = $ot_id LIMIT 1";
    $res = $conn->query($sql);

    if (!$res || $res->num_rows === 0) {
        responder(false, "La OT no existe.");
    }
}

/* ============================================================
   FUNCIÓN: obtenerCorrelativoViaje
   ============================================================ */
function obtenerCorrelativoViaje($conn, $ot_id)
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM ot_viajes
        WHERE orden_trabajo_id = $ot_id
    ";

    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    return intval($row["total"]) + 1;
}

/* ============================================================
   FUNCIÓN: insertarViaje
   ============================================================ */
function insertarViaje($conn, $data, $numero_viaje)
{
    $sql = "
        INSERT INTO ot_viajes (
            orden_trabajo_id,
            numero_viaje,
            fecha_viaje,
            semana_viaje,
            origen,
            destino,
            observaciones,
            estado_viaje,
            created_at
        ) VALUES (
            {$data['ot_id']},
            $numero_viaje,
            '{$data['fecha_viaje']}',
            '{$data['semana_viaje']}',
            '{$data['origen']}',
            '{$data['destino']}',
            '{$data['observaciones']}',
            'pendiente',
            NOW()
        )
    ";

    if (!$conn->query($sql)) {
        responder(false, "Error al crear viaje.");
    }

    return $conn->insert_id;
}

/* ============================================================
   FUNCIÓN: responder
   ============================================================ */
function responder($ok, $msg, $extra = array())
{
    $resp = array("ok" => $ok, "msg" => $msg);

    foreach ($extra as $k => $v) {
        $resp[$k] = $v;
    }

    echo json_encode($resp);
    exit;
}

/* ============================================================
   EJECUCIÓN DEL CONTROLADOR
   ============================================================ */

$data = validarInput();
validarOT($conn, $data["ot_id"]);

$numero_viaje = obtenerCorrelativoViaje($conn, $data["ot_id"]);
$viaje_id     = insertarViaje($conn, $data, $numero_viaje);

responder(true, "Viaje registrado correctamente.", array(
    "viaje_id"      => $viaje_id,
    "numero_viaje"  => $numero_viaje,
    "fecha_viaje"   => $data["fecha_viaje"],
    "semana_viaje"  => $data["semana_viaje"]
));
