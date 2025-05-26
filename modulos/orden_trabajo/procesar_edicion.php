<?php
session_start();
require_once '../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar datos principales
    $numeroOT   = trim($_POST['numero_ot']);
    $fecha      = trim($_POST['fecha']);
    $clienteID  = intval($_POST['cliente_id']);
    $tipoOTID   = intval($_POST['tipo_ot_id']);
    $empresaID  = intval($_POST['empresa_id']);
    $ocCliente  = trim($_POST['oc_cliente']); // Captura la Orden de Cliente (OC)

    // Validar datos esenciales
    if (empty($numeroOT) || empty($fecha) || empty($ocCliente) || $clienteID <= 0 || $tipoOTID <= 0 || $empresaID <= 0) {
        header("Location: editar_orden.php?numero_ot=$numeroOT&error=❌ Datos incompletos.");
        exit();
    }

    // Extraer el valor del campo dinámico según el tipo seleccionado
    // Inicializamos los campos dinámicos con cadenas vacías para limpiar los que no correspondan
    $numero_dam = "";
    $numero_booking = "";
    $otros = "";
    
    if ($tipoOTID === 2 && isset($_POST['numero_dam'])) {
        $numero_dam = trim($_POST['numero_dam']);
    } elseif ($tipoOTID === 3 && isset($_POST['numero_booking'])) {
        $numero_booking = trim($_POST['numero_booking']);
    } elseif ($tipoOTID === 1 && isset($_POST['otros'])) {
        $otros = trim($_POST['otros']);
    }

    // Calcular la nueva semana (ISO) basada en la fecha actualizada
    $semanaOT = date('W', strtotime($fecha));

    // Actualizar la orden, incluyendo los campos dinámicos
    $sqlUpdate = "UPDATE ordenes_trabajo 
                  SET fecha = ?, 
                      semana_ot = ?, 
                      cliente_id = ?, 
                      tipo_ot_id = ?, 
                      empresa_id = ?, 
                      oc_cliente = ?,
                      numero_dam = ?,
                      numero_booking = ?,
                      otros = ?
                  WHERE numero_ot = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    if (!$stmtUpdate) {
        header("Location: editar_orden.php?numero_ot=$numeroOT&error=❌ Error preparando la consulta.");
        exit();
    }

    // La cadena de tipos:
    // s   = fecha (cadena)
    // i   = semana_ot (entero)
    // i   = cliente_id (entero)
    // i   = tipo_ot_id (entero)
    // i   = empresa_id (entero)
    // s   = oc_cliente (cadena)
    // s   = numero_dam (cadena)
    // s   = numero_booking (cadena)
    // s   = otros (cadena)
    // s   = numero_ot (cadena)
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

    if ($stmtUpdate->execute()) {
        header("Location: orden_trabajo.php?success=✅ Orden actualizada correctamente. Semana asignada: $semanaOT.");
        exit();
    } else {
        header("Location: editar_orden.php?numero_ot=$numeroOT&error=❌ Error al actualizar la orden.");
        exit();
    }
} else {
    header("Location: editar_orden.php?error=Método no permitido.");
    exit();
}
?>

