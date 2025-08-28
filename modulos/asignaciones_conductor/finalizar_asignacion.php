<?php
// finalizar_asignacion.php

session_start();

// 1) Modo depuración (solo DEV)
// Puedes comentar esta sección en producción si no quieres mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors',   1);
ini_set('error_log',    __DIR__ . '/error_log.txt');

// 2) Cargar configuración, modelo y helpers
require_once __DIR__ . '/../../includes/config.php'; // define BASE_URL y getConnection()
require_once __DIR__ . '/modelo.php';                // define finalizarAsignacion()
require_once __DIR__ . '/funciones.php';              // define validarSesionAdmin(), setFlash(), getFlash()

// 3) Validar sesión y permisos
validarSesionAdmin();

// 4) Aceptar solo peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
    exit;
}

// 5) Validar parámetro asignacion_id
if (! isset($_POST['asignacion_id']) || ! ctype_digit($_POST['asignacion_id'])) {
    setFlash('danger', 'ID de asignación inválido.');
    header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
    exit;
}

// 6) Leer parámetros
$asignacionId = (int) $_POST['asignacion_id'];
$userId       = isset($_SESSION['usuario_id'])
                  ? (int) $_SESSION['usuario_id']
                  : 0;

// 7) Validar valores
if ($asignacionId <= 0 || $userId <= 0) {
    setFlash('danger', 'ID de asignación o usuario inválido.');
    header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
    exit;
}

// 8) Conexión a la base de datos
$conn = getConnection();
if (! $conn) {
    die('Error al conectar con la base de datos.');
}

// 9) Ejecutar la finalización
$result = finalizarAsignacion($conn, $asignacionId, $userId);

// 10) Responder JSON si es AJAX
$isAjax = ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
          && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => $result,
        'message' => $result
            ? 'Asignación finalizada correctamente.'
            : 'Error al finalizar la asignación.'
    ));
    exit;
}

// 11) Flash + redirección en llamadas normales
if ($result) {
    setFlash('success', 'Asignación finalizada correctamente.');
} else {
    setFlash('danger', 'Error al finalizar la asignación.');
}

header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
exit;