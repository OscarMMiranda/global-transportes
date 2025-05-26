<?php
session_start();
require_once '../../includes/conexion.php';

// Verificar si el usuario tiene permisos administrativos
if ($_SESSION['rol'] !== 'ADMIN') {
    echo "<script>alert('❌ No tienes permisos para restaurar órdenes eliminadas.'); window.location.href='orden_trabajo.php';</script>";
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('❌ Error: No se ha especificado la orden.'); window.location.href='orden_trabajo.php';</script>";
    exit();
}

$idOrden = intval($_GET['id']);

// Restaurar la orden a estado ACTIVA
$sqlActivar = "UPDATE ordenes_trabajo SET estado_ot = 'ACTIVA' WHERE id = ?";
$stmt = $conn->prepare($sqlActivar);
$stmt->bind_param("i", $idOrden);

if ($stmt->execute()) {
    header("Location: orden_trabajo.php?success=Orden restaurada correctamente");
    exit();
} else {
    echo "<script>alert('❌ Error al restaurar la orden.');</script>";
}
?>
