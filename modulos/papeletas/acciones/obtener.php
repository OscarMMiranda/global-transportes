<?php
// archivo: modulos/papeletas/acciones/obtener.php

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

ini_set('display_errors', 0);

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "msg" => "ID inválido"]);
    exit;
}

/* ============================================================
   CONSULTA PRINCIPAL (papeleta + estado)
   ============================================================ */
$sql = "
    SELECT 
        p.id,
        p.vehiculo_id,
        p.conductor_id,
        p.entidad_emisora_id,
        p.infraccion_id,
        p.fecha_infraccion,
        p.fecha_notificacion,
        p.fecha_vencimiento,
        p.fecha_pago,
        p.lugar,
        p.descripcion,
        p.monto,
        COALESCE(p.monto_descuento, 0) AS monto_descuento,
        COALESCE(p.monto_pagado, 0) AS monto_pagado_field,
        p.estado_id,
        ep.nombre AS estado_nombre,
        p.puntos,
        p.codigo_infraccion,
        p.codigo_papeleta,
        p.updated_at
    FROM papeletas p
    INNER JOIN papeleta_estado ep ON ep.id = p.estado_id
    WHERE p.id = $id
    LIMIT 1
";

$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    echo json_encode(["success" => false, "msg" => "No se encontró la papeleta"]);
    exit;
}

$data = mysqli_fetch_assoc($res);

/* ============================================================
   OBTENER SUMA REAL DE PAGOS Y ÚLTIMO PAGO
   ============================================================ */
$sqlPagos = "
    SELECT 
        COALESCE(SUM(monto), 0) AS suma_pagos,
        MAX(fecha) AS ultima_fecha,
        COUNT(*) AS cantidad_pagos
    FROM papeleta_pagos
    WHERE papeleta_id = {$data['id']}
";
$resPagos = mysqli_query($conn, $sqlPagos);
$pagosInfo = $resPagos && mysqli_num_rows($resPagos) ? mysqli_fetch_assoc($resPagos) : ['suma_pagos' => 0, 'ultima_fecha' => null, 'cantidad_pagos' => 0];

/* ============================================================
   NORMALIZAR Y CALCULAR MONTOS
   ============================================================ */
$monto = floatval($data['monto']);
$monto_descuento = floatval($data['monto_descuento']);
$monto_pagado_field = floatval($data['monto_pagado_field']);
$monto_pagado_sum = floatval($pagosInfo['suma_pagos']);

// Preferir la suma real de pagos si es mayor que el campo acumulado
$monto_pagado_real = max($monto_pagado_field, $monto_pagado_sum);

$total_a_pagar = $monto - $monto_descuento;
$saldo = $total_a_pagar - $monto_pagado_real;
if ($saldo < 0) $saldo = 0.00; // evitar negativos visuales

$porcentaje_pagado = ($total_a_pagar > 0) ? min(100, ($monto_pagado_real / $total_a_pagar) * 100) : 0;

/* ============================================================
   FUNCIONES AUXILIARES
   ============================================================ */
function convertirFecha($f) {
    if ($f === "" || $f === null || $f === "0000-00-00" || $f === "0000-00-00 00:00:00") {
        return "";
    }
    if (strpos($f, " ") !== false) {
        $f = substr($f, 0, 10);
    }
    if (strpos($f, "/") !== false) {
        list($d, $m, $y) = explode("/", $f);
        if (strlen($y) == 2) $y = "20" . $y;
        return "$y-$m-$d";
    }
    return $f;
}

function safeText($v) {
    return $v === null ? "" : htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

/* ============================================================
   PREPARAR RESPUESTA
   ============================================================ */
$response = [
    "success" => true,
    "data" => [
        "id" => intval($data['id']),
        "vehiculo_id" => intval($data['vehiculo_id']),
        "conductor_id" => intval($data['conductor_id']),
        "entidad_emisora_id" => intval($data['entidad_emisora_id']),
        "infraccion_id" => intval($data['infraccion_id']),
        "fecha_infraccion" => convertirFecha($data['fecha_infraccion']),
        "fecha_notificacion" => convertirFecha($data['fecha_notificacion']),
        "fecha_vencimiento" => convertirFecha($data['fecha_vencimiento']),
        "fecha_pago" => convertirFecha($data['fecha_pago']),
        "lugar" => safeText($data['lugar']),
        "descripcion" => safeText($data['descripcion']),
        "monto" => round($monto, 2),
        "monto_descuento" => round($monto_descuento, 2),
        "monto_pagado_field" => round($monto_pagado_field, 2),
        "monto_pagado_sum" => round($monto_pagado_sum, 2),
        "monto_pagado_real" => round($monto_pagado_real, 2),
        "total_a_pagar" => round($total_a_pagar, 2),
        "saldo" => round($saldo, 2),
        "porcentaje_pagado" => round($porcentaje_pagado, 2),
        "estado_id" => intval($data['estado_id']),
        "estado_nombre" => safeText($data['estado_nombre']),
        "puntos" => isset($data['puntos']) ? intval($data['puntos']) : null,
        "codigo_infraccion" => safeText($data['codigo_infraccion']),
        "codigo_papeleta" => safeText($data['codigo_papeleta']),
        "updated_at" => isset($data['updated_at']) ? $data['updated_at'] : null,
        "ultima_fecha_pago" => convertirFecha($pagosInfo['ultima_fecha']),
        "cantidad_pagos" => intval($pagosInfo['cantidad_pagos'])
    ]
];

echo json_encode($response);
exit;
?>
