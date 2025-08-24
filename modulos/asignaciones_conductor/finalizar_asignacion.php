<?php
// finalizar_asignacion.php

// session_start();

// 2) Modo depuración (solo DEV)
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors',     1);
    ini_set('error_log',      __DIR__ . '/error_log.txt');

    // 3) Cargar config.php (define getConnection() y rutas)
    require_once __DIR__ . '/../../includes/config.php';

     // 4) Obtener la conexión
    $conn = getConnection();

    require_once __DIR__ . '/modelo.php';                 // define finalizarAsignacion()
    require_once __DIR__ . '/funciones.php';               // define validarSesionAdmin(), setFlash(), getFlash()

    // --- Sesión y permisos ---
    validarSesionAdmin();

   
// --- Solo POST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
  
  exit;
}


// --- Lectura y validación del ID de asignación ---
if (empty($_POST['asignacion_id']) || !ctype_digit($_POST['asignacion_id'])) {
  setFlash('danger', 'ID de asignación inválido.');
  header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
  exit;
}
$asignacionId = (int) $_POST['asignacion_id'];




// --- Usuario que realiza la acción ---
if (empty($_SESSION['usuario_id'])) {
  // Ajusta a la clave de sesión correcta
  setFlash('danger', 'Usuario no autenticado.');
  header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
  exit;
}



// $userId = $_SESSION['usuario_id'];
$userId = (int) $_SESSION['usuario_id'];


// --- Conexión a la base de datos ---
$conn = getConnection();

if (!$conn) {
  die('Error al conectar con la base de datos.');
}

// --- Finalizar asignación ---
$result = finalizarAsignacion($conn, $asignacionId, $userId);


if ($result) {
    setFlash('success', 'Asignación finalizada correctamente.');
} else {
    setFlash('danger', 'Error al finalizar la asignación.');
}



// --- Volver al listado ---
header('Location: ' . BASE_URL . 'modulos/asignaciones_conductor/index.php');
exit;