<?php
// funciones.php — Utilidades globales

// echo 'funciones.php cargado correctamente';

// ✅ Validar sesión de usuario admin
function validarSesionAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['usuario']) || $_SESSION['rol_nombre'] !== 'admin') {
        header('Location: /login.php');
        exit;
    }
}

// 🧼 Sanitizar texto (compatible con PHP 5.6)
function sanitize($texto) {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

// 🧾 Registrar acción en historial general
function registrarEnHistorial($usuario, $accion, $modulo, $ip) {
    $conn = getConnection();
    $sql = "
        INSERT INTO historial_erp
            (usuario, accion, modulo, ip_origen, fecha)
        VALUES (?, ?, ?, ?, NOW())
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $usuario, $accion, $modulo, $ip);
    $stmt->execute();
    $stmt->close();
}

// 🧠 Obtener IP del cliente
function obtenerIP() {
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

// 📅 Formatear fecha para visualización
function formatearFecha($fecha) {
    if (!$fecha || $fecha === '0000-00-00') return '—';
    return date('d/m/Y', strtotime($fecha));
}

// 🔐 Validar que un ID sea entero positivo
function validarId($id) {
    return isset($id) && is_numeric($id) && intval($id) > 0;
}


function obtenerUsuarioId() {
    return isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 0;
}