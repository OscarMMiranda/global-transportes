<?php
// modulos/vehiculos/includes/funciones.php
// Helpers globales para el módulo Vehículos – sin acceso directo a datos

// 1) Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2) Validar sesión activa y rol admin
function validarSesionAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario']) || !is_array($_SESSION['usuario'])) {
        error_log("❌ Sesión no iniciada desde IP: " . $_SERVER['REMOTE_ADDR']);
        header("Location: /login.php");
        exit();
    }

    if (!isset($_SESSION['usuario']['rol_nombre']) || 
        strtolower($_SESSION['usuario']['rol_nombre']) !== 'admin') {
        error_log("❌ Acceso denegado. Rol incorrecto desde IP: " . $_SERVER['REMOTE_ADDR']);
        header("Location: /login.php");
        exit();
    }
}

// 3) Obtener ID del usuario logueado
function obtenerUsuarioId() {
    return (isset($_SESSION['usuario']['id']) && is_numeric($_SESSION['usuario']['id']))
        ? intval($_SESSION['usuario']['id'])
        : 0;
}

// 4) Obtener IP del cliente
function obtenerIP() {
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

// 5) Sanitizar texto para salida segura en HTML
function sanitize($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

// 6) Formatear fecha YYYY-MM-DD a DD/MM/YYYY
function formatearFecha($fecha) {
    if (empty($fecha) || $fecha === '0000-00-00') {
        return '—';
    }
    return date('d/m/Y', strtotime($fecha));
}

// 7) Validar que un ID sea entero positivo
function validarId($id) {
    return is_numeric($id) && intval($id) > 0;
}

// 8) Registrar acción genérica en historial del ERP
function registrarEnHistorial($conn, $usuario, $accion, $modulo, $ip) {
    $sql = "
        INSERT INTO historial_erp
            (usuario, accion, modulo, ip_origen, fecha)
        VALUES (?, ?, ?, ?, NOW())
    ";
    $stmt = $conn->prepare($sql);
    if (! $stmt) {
        error_log("❌ Error al preparar historial: " . $conn->error);
        return false;
    }

    $stmt->bind_param('ssss', $usuario, $accion, $modulo, $ip);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}