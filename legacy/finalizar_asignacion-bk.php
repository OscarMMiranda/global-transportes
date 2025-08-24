<?php
session_start();
require_once '../../includes/conexion.php'; // Conectar a la base de datos

// ACTIVAR VISUALIZACIÓN DE ERRORES
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para responder en formato JSON y salir
function responder_json($success, $message) {
    header('Content-Type: application/json');
    echo json_encode(["success" => $success, "message" => $message]);
    exit();
}

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") {
        responder_json(false, "Acceso denegado.");
    } else {
        // header("Location: ../sistema/login.php");

        header("Location: http://www.globaltransportes.com/login.php");

        
        exit();
    }
}

// Validar el parámetro ID de la asignación
if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") {
        responder_json(false, "ID de asignación inválido.");
    } else {
        die("ID de asignación inválido.");
    }
}

$asignacionId = intval($_GET['id']);

// Opcional: Verificar que la asignación exista
$sql_check = "SELECT estado_id FROM asignaciones_conductor WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $asignacionId);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows === 0) {
    $stmt_check->close();
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") {
        responder_json(false, "Asignación no encontrada.");
    } else {
        die("No se encontró la asignación con el ID proporcionado.");
    }
}
$stmt_check->close();

// Obtener el ID del estado "finalizado"
$sql_estado_finalizado = "SELECT id FROM estado_asignacion WHERE nombre = 'finalizado'";
$result_estado_finalizado = $conn->query($sql_estado_finalizado);
if (!$result_estado_finalizado || $result_estado_finalizado->num_rows === 0) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") {
        responder_json(false, "No se encontró el estado 'finalizado'.");
    } else {
        die("No se encontró el estado 'finalizado' en la base de datos.");
    }
}
$row_estado_finalizado = $result_estado_finalizado->fetch_assoc();
$estadoFinalizadoId = $row_estado_finalizado['id'];

// Actualizar la asignación: cambiar el estado a finalizado y asignar fecha_fin = NOW()
$sql_update = "UPDATE asignaciones_conductor SET estado_id = ?, fecha_fin = NOW() WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
if (!$stmt_update) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") {
        responder_json(false, "Error en la preparación de la consulta: " . $conn->error);
    } else {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
}
$stmt_update->bind_param("ii", $estadoFinalizadoId, $asignacionId);
if (!$stmt_update->execute()) {
    $stmt_update->close();
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") {
        responder_json(false, "Error al finalizar la asignación: " . $stmt_update->error);
    } else {
        die("Error al finalizar la asignación: " . $stmt_update->error);
    }
}
$stmt_update->close();

// Si la solicitud es AJAX, respondemos con JSON; si no, redirigimos.
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") {
    responder_json(true, "Asignación finalizada con éxito.");
} else {
    header("Location: asignaciones.php");
    exit();
}
?>

