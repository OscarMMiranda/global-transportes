<?php
// archivo: /modulos/orden_trabajo/controllers/EditController.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/conexion.php';
$conn = getConnection();

/**
 * Redirige con mensaje de error y registra en log
 * @param string $numeroOT
 * @param string $mensaje
 */
function redirigirError($numeroOT, $mensaje) {
    error_log("[$numeroOT] $mensaje");
    header("Location: edit.php?numero_ot=$numeroOT&error=$mensaje");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Capturar datos principales
	$numeroOT   = isset($_POST['numero_ot']) ? trim($_POST['numero_ot']) : '';
	$fecha      = isset($_POST['fecha']) ? trim($_POST['fecha']) : '';
	$clienteID  = isset($_POST['cliente_id']) ? intval($_POST['cliente_id']) : 0;
	$tipoOTID   = isset($_POST['tipo_ot_id']) ? intval($_POST['tipo_ot_id']) : 0;
	$empresaID  = isset($_POST['empresa_id']) ? intval($_POST['empresa_id']) : 0;
	$ocCliente  = isset($_POST['oc_cliente']) ? trim($_POST['oc_cliente']) : '';

    // Validar datos esenciales
    if (empty($numeroOT) || empty($fecha) || empty($ocCliente) || $clienteID <= 0 || $tipoOTID <= 0 || $empresaID <= 0) {
        redirigirError($numeroOT, '❌ Datos incompletos.');
    }

    // Inicializar campos dinámicos
    $numero_dam = '';
    $numero_booking = '';
    $otros = '';

    // Asignar campo dinámico según tipo de OT
    if ($tipoOTID === 2 && isset($_POST['numero_dam'])) {
    $numero_dam = trim($_POST['numero_dam']);
} elseif ($tipoOTID === 3 && isset($_POST['numero_booking'])) {
    $numero_booking = trim($_POST['numero_booking']);
} elseif ($tipoOTID === 1 && isset($_POST['otros'])) {
    $otros = trim($_POST['otros']);
}

    // Calcular semana ISO
    $semanaOT = intval(date('W', strtotime($fecha)));

    // Preparar consulta
    $sqlUpdate = "
        UPDATE ordenes_trabajo 
        SET fecha = ?, 
            semana_ot = ?, 
            cliente_id = ?, 
            tipo_ot_id = ?, 
            empresa_id = ?, 
            oc_cliente = ?,
            numero_dam = ?,
            numero_booking = ?,
            otros = ?
        WHERE numero_ot = ?
    ";

    // Registrar SQL y POST para depuración
    file_put_contents(__DIR__ . '/debug_sql.txt', $sqlUpdate);
    file_put_contents(__DIR__ . '/debug_post.txt', print_r($_POST, true));

    $stmtUpdate = $conn->prepare($sqlUpdate);
    if (!$stmtUpdate) {
        redirigirError($numeroOT, '❌ Error preparando la consulta: ' . $conn->error);
    }

    // Bind de parámetros
    $stmtUpdate->bind_param("siiissssss", 
        $fecha, 
        $semanaOT, 
        $clienteID, 
        $tipoOTID, 
        $empresaID, 
        $ocCliente, 
        $numero_dam, 
        $numero_booking, 
        $otros, 
        $numeroOT
    );

    // Ejecutar y redirigir
    if ($stmtUpdate->execute()) {
        header("Location: /modulos/orden_trabajo/index.php?success=✅ Orden actualizada correctamente. Semana asignada: $semanaOT");
        exit();
    } else {
        redirigirError($numeroOT, '❌ Error al actualizar la orden: ' . $stmtUpdate->error);
    }
} else {
    header("Location: edit.php?error=❌ Método no permitido.");
    exit();
}
?>