<?php
// ======================================================
//  CONTROLADOR: EditarController.php
//  RESPONSABILIDAD: Obtener datos completos de una OT
//  GLOBAL 2026 — Arquitectura Limpia (Optimizado)
// ======================================================

require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

header('Content-Type: application/json');

// ======================================================
// FUNCIÓN: Respuesta JSON
// ======================================================
function responder($ok, $msg, $extra = [])
{
    echo json_encode(array_merge([
        "ok" => $ok,
        "msg" => $msg
    ], $extra));
    exit;
}

// ======================================================
// VALIDAR ID
// ======================================================
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id <= 0) {
    responder(false, "ID inválido");
}

// ======================================================
// FUNCIÓN: Obtener OT completa
// ======================================================
function getOTCompleta($conn, $id)
{
    $sql = "
    SELECT 
        ot.*,
        c.nombre AS cliente_nombre,
        e.razon_social AS empresa_nombre,
        t.nombre AS tipo_ot_nombre,
        est.nombre AS estado_nombre
    FROM ordenes_trabajo ot
    LEFT JOIN clientes c ON c.id = ot.cliente_id
    LEFT JOIN empresa e ON e.id = ot.empresa_id
    LEFT JOIN tipo_ot t ON t.id = ot.tipo_ot_id
    LEFT JOIN estado_orden_trabajo est ON est.id = ot.estado_ot
    WHERE ot.id = $id
    LIMIT 1
    ";

    $res = $conn->query($sql);

    if (!$res || $res->num_rows === 0) {
        responder(false, "No se encontró la OT");
    }

    return $res->fetch_assoc();
}

// ======================================================
// FUNCIÓN: Formatear semana corporativa S01-2026
// ======================================================
function formatearSemanaCorporativa($fecha, $semana_raw)
{
    if (!$semana_raw || $semana_raw === "0") {
        return "S00-" . substr($fecha, 0, 4);
    }

    $anio = substr($fecha, 0, 4);

    // Caso ISO: 2026-W02
    if (strpos($semana_raw, "W") !== false) {
        $partes = explode('-', $semana_raw); // ["2026", "W02"]
        $semana_num = str_replace("W", "", $partes[1]);
    }
    // Caso numérico: 1, 01, 2, 12
    else {
        $semana_num = intval($semana_raw);
    }

    $semana_num = str_pad($semana_num, 2, "0", STR_PAD_LEFT);

    return "S" . $semana_num . "-" . $anio;
}

// ======================================================
// FUNCIÓN: Catálogo Clientes
// ======================================================
function getCatalogoClientes($conn)
{
    $sql = "
    SELECT id, nombre
    FROM clientes
    WHERE estado = 'Activo'
    AND deleted_at IS NULL
    ORDER BY nombre ASC
    ";

    $res = $conn->query($sql);
    $out = [];

    while ($res && $row = $res->fetch_assoc()) {
        $out[] = [
            "id"     => intval($row["id"]),
            "nombre" => $row["nombre"]
        ];
    }

    return $out;
}

// ======================================================
// FUNCIÓN: Catálogo Empresas
// ======================================================
function getCatalogoEmpresas($conn)
{
    $sql = "
    SELECT id, razon_social AS nombre
    FROM empresa
    ORDER BY razon_social ASC
    ";

    $res = $conn->query($sql);
    $out = [];

    while ($res && $row = $res->fetch_assoc()) {
        $out[] = [
            "id"     => intval($row["id"]),
            "nombre" => $row["nombre"]
        ];
    }

    return $out;
}

// ======================================================
// FUNCIÓN: Catálogo Tipos OT
// ======================================================
function getCatalogoTiposOT($conn)
{
    $sql = "
    SELECT id, nombre
    FROM tipo_ot
    ORDER BY nombre ASC
    ";

    $res = $conn->query($sql);
    $out = [];

    while ($res && $row = $res->fetch_assoc()) {
        $out[] = [
            "id"     => intval($row["id"]),
            "nombre" => $row["nombre"]
        ];
    }

    return $out;
}

// ======================================================
// EJECUCIÓN PRINCIPAL
// ======================================================
$data = getOTCompleta($conn, $id);

// Formatear semana corporativa
$data["semana_ot"] = formatearSemanaCorporativa($data["fecha"], $data["semana_ot"]);

// Catálogos
$clientes  = getCatalogoClientes($conn);
$empresas  = getCatalogoEmpresas($conn);
$tipos_ot  = getCatalogoTiposOT($conn);

// Respuesta final
responder(true, "OK", [
    "data"     => $data,
    "clientes" => $clientes,
    "empresas" => $empresas,
    "tipos_ot" => $tipos_ot
]);
