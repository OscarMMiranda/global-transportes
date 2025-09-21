<?php
// archivo: actualizar.php — actualización trazable con fecha de cierre condicional

require_once __DIR__ . '/../../../includes/config.php';

$conn = getConnection();
if (!($conn instanceof mysqli)) {

// Validar ID
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo "ID inválido.";
    return;
}

// Obtener estado nuevo
$estadoNuevo = isset($_POST['estado']) ? trim($_POST['estado']) : '';
if ($estadoNuevo === '') {
    echo "Estado no definido.";
    return;
}

// Determinar fecha de cierre
$fechaCierre = ($estadoNuevo === 'I' || strtolower($estadoNuevo) === 'inactivo') ? date('Y-m-d H:i:s') : null;

// Construir SQL
$sql = "UPDATE entidades SET 
            estado = '$estadoNuevo',
            fecha_modificacion = NOW(),
            fecha_cierre = " . ($fechaCierre ? "'$fechaCierre'" : "NULL") . "
        WHERE id = $id";

// Ejecutar
if ($conn->query($sql)) {
    echo "✅ Entidad actualizada correctamente.";
} else {
    echo "❌ Error al actualizar: " . $conn->error;
}