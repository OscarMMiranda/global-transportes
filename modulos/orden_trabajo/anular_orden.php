<?php
session_start();
require_once '../../includes/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Error: ID de orden no proporcionado.");
}

$idOrden = intval($_GET['id']);

// Actualizar el estado de la orden a "Anulada" (ID = 7)
$sqlActualizar = "UPDATE ordenes_trabajo SET estado_ot = 7 WHERE id = ?";
$stmt = $conn->prepare($sqlActualizar);
$stmt->bind_param("i", $idOrden);
if ($stmt->execute()) {
    header("Location: orden_trabajo.php?success=✅ Orden anulada correctamente.");
} else {
    header("Location: orden_trabajo.php?error=❌ Error al anular la orden.");
}
?>
