<?php
session_start();
require_once '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturamos los datos del formulario
    $numeroCorrelativo = trim($_POST['numero_correlativo']); // Número correlativo
    $anioOT          = trim($_POST['anio_ot']);               // Año de la OT
    $fecha           = trim($_POST['fecha']);
    $clienteID       = intval($_POST['cliente_id']);
    $tipoOTID        = intval($_POST['tipo_ot_id']);
    $empresaID       = intval($_POST['empresa_id']);
    $ocCliente       = trim($_POST['oc_cliente']);             // Orden de Cliente (OC)

    // Validamos que todos los datos esenciales estén completos
    if (empty($numeroCorrelativo) || empty($anioOT) || empty($fecha) || empty($ocCliente)
        || $clienteID <= 0 || $tipoOTID <= 0 || $empresaID <= 0) {
        header("Location: crear_orden.php?error=Datos incompletos, verifica los campos.");
        exit();
    }

    // Formateamos el número OT (por ejemplo: 5-2025)
    $numeroOT = $numeroCorrelativo . '-' . $anioOT;

    // Calculamos la semana ISO correspondiente a la fecha y convertimos a entero
    $semanaOT = (int) date('W', strtotime($fecha));

    // Inicializamos las variables para los campos dinámicos en cadena vacía
    $numero_dam     = "";
    $numero_booking = "";
    $otros          = "";

    // Capturamos el valor del campo dinámico según el tipo de OT:
    // - Si es Importación (por ejemplo, tipo_ot_id === 2) → Guardamos el "Número DAM"
    // - Si es Exportación (por ejemplo, tipo_ot_id === 3) → Guardamos el "Número de Booking"
    // - Si es Nacional (por ejemplo, tipo_ot_id === 1) → Guardamos el valor de "Otros"
    if ($tipoOTID === 2 && isset($_POST['numero_dam'])) {
        $numero_dam = trim($_POST['numero_dam']);
    } else if ($tipoOTID === 3 && isset($_POST['numero_booking'])) {
        $numero_booking = trim($_POST['numero_booking']);
    } else if ($tipoOTID === 1 && isset($_POST['otros'])) {
        $otros = trim($_POST['otros']);
    }

    // Verificamos si el número de OT ya existe para evitar duplicados
    $check_sql  = "SELECT id FROM ordenes_trabajo WHERE numero_ot = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $numeroOT);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        header("Location: crear_orden.php?error=El número de OT ya existe. Por favor, usa otro número.");
        exit();
    }

    // En este ejemplo, se establece estado_ot = 1 para la orden creada.
    // Se inserta la nueva orden incluyendo el campo de Orden de Cliente y los campos dinámicos.
    $sql  = "INSERT INTO ordenes_trabajo 
             (numero_ot, fecha, semana_ot, estado_ot, cliente_id, tipo_ot_id, empresa_id, oc_cliente, numero_dam, numero_booking, otros)
             VALUES (?, ?, ?, 1, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Cadena de tipos:
    // "s" para numero_ot, "s" para fecha, "i" para semana_ot,
    // "i" para cliente_id, "i" para tipo_ot_id, "i" para empresa_id,
    // "s" para oc_cliente, "s" para numero_dam, "s" para numero_booking, "s" para otros.
    $stmt->bind_param("ssiiiissss", $numeroOT, $fecha, $semanaOT, $clienteID, $tipoOTID, $empresaID, $ocCliente, $numero_dam, $numero_booking, $otros);

    if ($stmt->execute()) {
        header("Location: orden_trabajo.php?success=✅ Orden creada correctamente en la semana $semanaOT.");
        exit();
    } else {
        header("Location: crear_orden.php?error=❌ Error al guardar la orden.");
        exit();
    }
} else {
    header("Location: crear_orden.php?error=Método no permitido.");
    exit();
}
?>
