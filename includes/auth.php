<?php
// includes/auth.php

/**
 * requireLogin()
 *
 * Verifica que el usuario esté autenticado (sesión activa).
 * Si no existe la clave 'usuario' en $_SESSION,
 * redirige a la página de login y detiene la ejecución.
 */
function requireLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['usuario'])) {
        header("Location: http://www.globaltransportes.com/login.php");
        exit;
    }
}

/**
 * requireAdmin()
 *
 * Verifica que el usuario esté autenticado y tenga rol 'admin'.
 * Primero llama a requireLogin() para asegurar la sesión,
 * luego comprueba $_SESSION['rol_nombre'].
 * Si el rol no es 'admin', redirige al login.
 */
function requireAdmin()
{
    // Asegura sesión y usuario
    requireLogin();

    // Verifica rol
    if (empty($_SESSION['rol_nombre']) || strtolower($_SESSION['rol_nombre']) !== 'admin') {

        header("Location: http://www.globaltransportes.com/login.php");

        // header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

/**
 * isAdmin()
 *
 * Retorna true si el usuario autenticado tiene rol 'admin'.
 * No redirige, solo verifica.
 *
 * @return bool
 */
function isAdmin()
{
    return (
        isset($_SESSION['usuario']) &&
        isset($_SESSION['rol_nombre']) &&
        strtolower($_SESSION['rol_nombre']) === 'admin'
    );
}
