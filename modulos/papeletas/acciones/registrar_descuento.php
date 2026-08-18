<?php
// archivo: /modulos/papeletas/acciones/registrar_descuento.php
header('Content-Type: application/json; charset=utf-8');

// Buffer para evitar salidas accidentales
ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';

// Iniciar sesión solo si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Evitar que notices/warnings se impriman en la salida JSON
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$conn = getConnection();

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'Método no permitido']);
    exit;
}

// Leer y sanitizar inputs
$papeleta_id    = isset($_POST['papeleta_id']) ? intval($_POST['papeleta_id']) : 0;
$tipo           = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
$monto_input    = isset($_POST['monto']) ? trim($_POST['monto']) : '';
$porcentaje_in  = isset($_POST['porcentaje']) ? trim($_POST['porcentaje']) : '';
$fecha          = isset($_POST['fecha']) ? trim($_POST['fecha']) : '';
$observacion    = isset($_POST['observacion']) ? trim($_POST['observacion']) : '';
$usuario_id     = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

// Validaciones básicas
if ($papeleta_id <= 0) {
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'ID de papeleta inválido']);
    exit;
}
if ($tipo === '') {
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'Seleccione el tipo de descuento']);
    exit;
}
if ($fecha === '') {
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'Seleccione la fecha del descuento']);
    exit;
}

// Obtener datos actuales de la papeleta para validar saldo
$sql = "SELECT monto, COALESCE(monto_descuento,0) AS monto_descuento, COALESCE(monto_pagado,0) AS monto_pagado FROM papeletas WHERE id = $papeleta_id LIMIT 1";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'Papeleta no encontrada']);
    exit;
}
$row = mysqli_fetch_assoc($res);
$total = floatval($row['monto']);
$descActual = floatval($row['monto_descuento']);
$pagado = floatval($row['monto_pagado']);
$totalAPagar = $total - $descActual;
$saldo = $totalAPagar - $pagado;
if ($saldo < 0) $saldo = 0.0;

// Normalizar entradas numéricas
$monto_input = str_replace(',', '.', $monto_input);
$porcentaje_in = str_replace(',', '.', $porcentaje_in);

// Determinar si porcentaje es válido numérico
$porcentaje_valido = false;
$porcentaje = null;
if ($porcentaje_in !== '') {
    if (is_numeric($porcentaje_in)) {
        $porcentaje = floatval($porcentaje_in);
        if ($porcentaje > 0) $porcentaje_valido = true;
    }
}

// Calcular monto final
if ($porcentaje_valido) {
    $monto = round(($saldo * ($porcentaje / 100)), 2);
} else {
    $monto = ($monto_input !== '') ? floatval($monto_input) : 0.0;
}

// Validar monto
if ($monto <= 0) {
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'Monto de descuento inválido']);
    exit;
}
if ($monto > $saldo) {
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'El monto de descuento excede el saldo pendiente']);
    exit;
}

// Preparar valores seguros para SQL
$tipo_sql = mysqli_real_escape_string($conn, $tipo);
$fecha_sql = mysqli_real_escape_string($conn, $fecha);
$observacion_sql = mysqli_real_escape_string($conn, $observacion);
$usuario_sql = ($usuario_id !== null) ? intval($usuario_id) : "NULL";

// Verificar si las columnas 'porcentaje' y 'observacion' existen en la tabla papeleta_descuentos
$col_porcentaje = false;
$col_observacion = false;

$checkPct = mysqli_query($conn, "SHOW COLUMNS FROM `papeleta_descuentos` LIKE 'porcentaje'");
if ($checkPct && mysqli_num_rows($checkPct) > 0) $col_porcentaje = true;

$checkObs = mysqli_query($conn, "SHOW COLUMNS FROM `papeleta_descuentos` LIKE 'observacion'");
if ($checkObs && mysqli_num_rows($checkObs) > 0) $col_observacion = true;

// Iniciar transacción
mysqli_autocommit($conn, false);
$ok = true;
$error = '';

// Construir INSERT dinámico según columnas disponibles
$columns = ['papeleta_id', 'tipo', 'monto', 'fecha', 'creado_en', 'creado_por'];
$values  = [$papeleta_id, "'$tipo_sql'", $monto, "'$fecha_sql'", 'NOW()', ($usuario_sql === "NULL" ? "NULL" : $usuario_sql)];

// Si existe porcentaje, insertarlo
if ($col_porcentaje) {
    array_splice($columns, 3, 0, 'porcentaje'); // insertar antes de fecha
    array_splice($values, 3, 0, ($porcentaje !== null ? floatval($porcentaje) : "NULL"));
}

// Si existe observacion, insertarla
if ($col_observacion) {
    // insertar observacion antes de creado_en
    $insertPos = array_search('creado_en', $columns);
    array_splice($columns, $insertPos, 0, 'observacion');
    array_splice($values, $insertPos, 0, "'" . $observacion_sql . "'");
}

// Preparar SQL final
$cols_sql = implode(', ', $columns);
$vals_sql = implode(', ', $values);
$sqlIns = "INSERT INTO papeleta_descuentos ($cols_sql) VALUES ($vals_sql)";

if (!mysqli_query($conn, $sqlIns)) {
    $ok = false;
    $error = mysqli_error($conn);
}

// Actualizar monto_descuento acumulado en la tabla papeletas
if ($ok) {
    $sqlUpdate = "UPDATE papeletas SET monto_descuento = COALESCE(monto_descuento,0) + $monto WHERE id = $papeleta_id";
    if (!mysqli_query($conn, $sqlUpdate)) {
        $ok = false;
        $error = mysqli_error($conn);
    }
}

// Recalcular estado
if ($ok) {
    $resSum = mysqli_query($conn, "SELECT COALESCE(SUM(monto),0) AS suma_pagos FROM papeleta_pagos WHERE papeleta_id = $papeleta_id");
    $suma_pagos = 0;
    if ($resSum && mysqli_num_rows($resSum)) {
        $r = mysqli_fetch_assoc($resSum);
        $suma_pagos = floatval($r['suma_pagos']);
    }

    $resDesc = mysqli_query($conn, "SELECT COALESCE(monto_descuento,0) AS monto_descuento FROM papeletas WHERE id = $papeleta_id");
    $monto_desc_final = 0;
    if ($resDesc && mysqli_num_rows($resDesc)) {
        $rd = mysqli_fetch_assoc($resDesc);
        $monto_desc_final = floatval($rd['monto_descuento']);
    }

    $totalAPagarFinal = $total - $monto_desc_final;
    $saldoFinal = $totalAPagarFinal - $suma_pagos;
    if ($saldoFinal <= 0) {
        $estado_id = 4; // ajustar según catálogo: 4 = Pagada
    } else {
        $estado_id = 6; // ajustar según catálogo: 6 = Parcial/Vencida
    }

    if (!mysqli_query($conn, "UPDATE papeletas SET estado_id = $estado_id WHERE id = $papeleta_id")) {
        $ok = false;
        $error = mysqli_error($conn);
    }
}

// Commit o rollback
if ($ok) {
    mysqli_commit($conn);
    mysqli_autocommit($conn, true);
    ob_end_clean();
    echo json_encode([
        'success' => true,
        'ok' => true,
        'msg' => 'Descuento registrado correctamente',
        'monto_aplicado' => $monto,
        'porcentaje' => ($porcentaje !== null ? $porcentaje : null)
    ]);
    exit;
} else {
    mysqli_rollback($conn);
    mysqli_autocommit($conn, true);
    ob_end_clean();
    echo json_encode(['success' => false, 'ok' => false, 'msg' => 'Error al registrar descuento: ' . $error]);
    exit;
}
?>
