<?php
session_start();

// 1) Modo depuración (solo DEV)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',     1);
ini_set('error_log',      __DIR__ . '/error_log.txt');

// 2) Cargar configuración y conexión
require_once __DIR__ . '/../../includes/config.php';
$conn = getConnection();


// Verificar acceso solo para administradores
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    header("Location: ../sistema/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $conductor_id = intval($_GET['id']);

    // Verificar si el conductor existe antes de actualizar
    $sql_check = "SELECT id FROM conductores WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $conductor_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si el conductor existe, proceder con la actualización
        $sql_update = "UPDATE conductores SET activo = 0 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $conductor_id);

        if ($stmt_update->execute()) {
            header("Location: conductores.php?mensaje=Conductor dado de baja");
            exit();
        } else {
            echo "❌ Error al actualizar el estado.";
        }
        
        $stmt_update->close();
    } else {
        echo "❌ Conductor no encontrado.";
    }

    $stmt_check->close();
} else {
    echo "❌ ID de conductor no proporcionado.";
}

$conn->close();
?>
