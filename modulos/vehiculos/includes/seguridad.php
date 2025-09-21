<?php
// archivo: /includes/seguridad.php

// Validar que el usuario esté autenticado
function validarSesionUsuario() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: /login.php");
        exit();
    }
}

// Validar que el usuario tenga rol de administrador
function validarSesionAdmin() {
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== 'admin') {
        header("Location: /login.php");
        exit();
    }
}

// Validar que el usuario tenga un rol específico
function validarSesionRol($rolEsperado) {
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol_nombre']) || $_SESSION['rol_nombre'] !== $rolEsperado) {
        header("Location: /login.php");
        exit();
    }
}

// Obtener IP del usuario (compatible con proxies)
function obtenerIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

// Obtener User Agent del navegador
function obtenerUserAgent() {
    return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'desconocido';
}



?>