<?php
// archivo: /includes/funciones.php
// --------------------------------------------------------------
// Funciones globales del sistema (compatibles con PHP 5.6)
// --------------------------------------------------------------
// Incluir conexión



require_once __DIR__ . '/conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --------------------------------------------------------------
// OBTENER IP DEL CLIENTE (compatible con PHP 5.6)
// --------------------------------------------------------------
function obtenerIP()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

// --------------------------------------------------------------
// SANITIZAR CADENA
// --------------------------------------------------------------
function limpiarCadena($cadena)
{
    if (!isset($cadena)) {
        return '';
    }

    return htmlspecialchars(trim($cadena), ENT_QUOTES, 'UTF-8');
}

// --------------------------------------------------------------
// RESPUESTA JSON (para AJAX)
// --------------------------------------------------------------
function jsonResponse($estado, $mensaje, $extra = array())
{
    header('Content-Type: application/json');

    $respuesta = array(
        'estado'  => $estado,
        'mensaje' => $mensaje
    );

    if (is_array($extra)) {
        foreach ($extra as $k => $v) {
            $respuesta[$k] = $v;
        }
    }

    echo json_encode($respuesta);
    exit;
}

// --------------------------------------------------------------
// DETECTAR SI ES AJAX
// --------------------------------------------------------------
function isAjax()
{
    return (
        isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    );
}

// --------------------------------------------------------------
// REGISTRAR EN HISTORIAL (AUDITORÍA GLOBAL)
// --------------------------------------------------------------
function registrarEnHistorial($conn, $usuario, $accion, $modulo, $ip)
{
    if (!$conn) {
        error_log("❌ registrarEnHistorial(): conexión inválida");
        return false;
    }

    if (!$usuario || !$accion || !$modulo) {
        error_log("❌ registrarEnHistorial(): parámetros incompletos");
        return false;
    }

    $sql = "INSERT INTO historial_bd (usuario, accion, tabla_afectada, ip_usuario, fecha)
            VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("❌ Error preparando historial: " . $conn->error);
        return false;
    }

    $stmt->bind_param("ssss", $usuario, $accion, $modulo, $ip);

    if (!$stmt->execute()) {
        error_log("❌ Error ejecutando historial: " . $stmt->error);
        return false;
    }

    return true;
}

// --------------------------------------------------------------
// REDIRECCIÓN SEGURA
// --------------------------------------------------------------
function redirect($url)
{
    header("Location: " . $url);
    exit;
}

// --------------------------------------------------------------
// DEBUG (solo para desarrollo)
// --------------------------------------------------------------
function debug($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}