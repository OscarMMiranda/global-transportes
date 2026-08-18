<?php
// archivo: modulos/papeletas/acciones/registrar_pago.php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'msg' => 'Método no permitido']);
    exit;
}

session_start();

$id_papeleta   = isset($_POST['id_papeleta']) ? intval($_POST['id_papeleta']) : 0;
$monto_pagado  = isset($_POST['monto_pagado']) ? floatval($_POST['monto_pagado']) : 0;
$fecha_pago    = isset($_POST['fecha_pago']) ? $_POST['fecha_pago'] : '';
$observacion   = isset($_POST['observacion']) ? trim($_POST['observacion']) : '';

if ($id_papeleta <= 0) {
    echo json_encode(['success' => false, 'msg' => 'ID de papeleta inválido']);
    exit;
}

if ($monto_pagado <= 0) {
    echo json_encode(['success' => false, 'msg' => 'El monto pagado debe ser mayor a 0']);
    exit;
}

if ($fecha_pago == '') {
    echo json_encode(['success' => false, 'msg' => 'Debe indicar la fecha de pago']);
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
$conn = getConnection();

// Obtener datos de la papeleta
$sql = "
    SELECT monto, monto_descuento, monto_pagado, estado_id
    FROM papeletas
    WHERE id = $id_papeleta
    LIMIT 1
";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    echo json_encode(['success' => false, 'msg' => 'La papeleta no existe']);
    exit;
}

$row = mysqli_fetch_assoc($res);

$monto_total       = floatval($row['monto']);
$monto_descuento   = floatval($row['monto_descuento']);
$monto_pagado_acum = floatval($row['monto_pagado']);
$estado_actual     = intval($row['estado_id']);

// Total real a pagar
$total_a_pagar = $monto_total - $monto_descuento;

// Si ya está pagada, no permitir más pagos
if ($estado_actual == 4) { // 4 = PAGADA
    echo json_encode(['success' => false, 'msg' => 'La papeleta ya está pagada.']);
    exit;
}

// Validar que el pago no exceda el saldo
$saldo = $total_a_pagar - $monto_pagado_acum;

if ($monto_pagado > $saldo) {
    echo json_encode(['success' => false, 'msg' => 'El monto excede el saldo pendiente.']);
    exit;
}

// Registrar pago individual
// Sanitizar valores para evitar inyección en la consulta construida
$fecha_pago_sql = mysqli_real_escape_string($conn, substr($fecha_pago, 0, 10)); // DATE
$observacion_sql = mysqli_real_escape_string($conn, $observacion);

$sqlPago = "
    INSERT INTO papeleta_pagos 
    (papeleta_id, fecha, monto, observacion)
    VALUES 
    ($id_papeleta, '$fecha_pago_sql', $monto_pagado, '$observacion_sql')
";

if (!mysqli_query($conn, $sqlPago)) {
    echo json_encode([
        'success' => false,
        'msg' => 'Error al registrar el pago: ' . mysqli_error($conn)
    ]);
    exit;
}

// Actualizar monto_pagado acumulado
$sqlUpdateMonto = "
    UPDATE papeletas
    SET monto_pagado = monto_pagado + $monto_pagado
    WHERE id = $id_papeleta
";
mysqli_query($conn, $sqlUpdateMonto);

// Recalcular monto pagado final
$sqlFinal = "
    SELECT monto_pagado
    FROM papeletas
    WHERE id = $id_papeleta
";
$resFinal = mysqli_query($conn, $sqlFinal);
$rowFinal = mysqli_fetch_assoc($resFinal);

$monto_pagado_final = floatval($rowFinal['monto_pagado']);

// Cambiar estado automáticamente
if ($monto_pagado_final >= $total_a_pagar) {
    // Pagada
    mysqli_query($conn, "
        UPDATE papeletas 
        SET estado_id = 4
        WHERE id = $id_papeleta
    ");
} else {
    // Parcialmente pagada
    mysqli_query($conn, "
        UPDATE papeletas 
        SET estado_id = 6
        WHERE id = $id_papeleta
    ");
}

/* ============================
   Inserción en papeleta_historial
   ============================ */
// Preparar comentario para el historial
$comentario_hist = "Pago registrado: S/ " . number_format($monto_pagado, 2, '.', ',');
if (!empty($observacion)) {
    $comentario_hist .= " - " . mb_substr($observacion, 0, 500, 'UTF-8'); // limitar longitud
}
$comentario_hist_sql = mysqli_real_escape_string($conn, $comentario_hist);

// Comprobar si la columna usuario_id existe en la tabla papeleta_historial
$hasUsuarioId = false;
$colCheck = mysqli_query($conn, "SHOW COLUMNS FROM papeleta_historial LIKE 'usuario_id'");
if ($colCheck && mysqli_num_rows($colCheck) > 0) {
    $hasUsuarioId = true;
    mysqli_free_result($colCheck);
}

// Obtener usuario en sesión si existe
$usuario_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

// Construir e insertar según esquema
if ($hasUsuarioId) {
    if ($usuario_id === null) {
        $sqlHist = "INSERT INTO papeleta_historial (papeleta_id, estado_id, comentario, fecha, usuario_id)
                    VALUES ($id_papeleta, 1, '$comentario_hist_sql', NOW(), NULL)";
    } else {
        $sqlHist = "INSERT INTO papeleta_historial (papeleta_id, estado_id, comentario, fecha, usuario_id)
                    VALUES ($id_papeleta, 1, '$comentario_hist_sql', NOW(), $usuario_id)";
    }
} else {
    $sqlHist = "INSERT INTO papeleta_historial (papeleta_id, estado_id, comentario, fecha)
                VALUES ($id_papeleta, 1, '$comentario_hist_sql', NOW())";
}

if (!mysqli_query($conn, $sqlHist)) {
    // No interrumpimos el flujo de pago; registramos el error para depuración
    error_log("[registrar_pago] Error insertar historial: " . mysqli_error($conn) . " | SQL: " . $sqlHist);
    // Puedes opcionalmente incluir un campo en la respuesta para indicar fallo en historial
} else {
    // opcional: $hist_id = mysqli_insert_id($conn);
}

/* ============================
   Respuesta final
   ============================ */
echo json_encode([
    'success' => true,
    'msg' => 'Pago registrado correctamente'
]);

exit;
?>

