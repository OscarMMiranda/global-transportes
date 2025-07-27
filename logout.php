<?php
session_start();

// Incluimos conexión y helpers para registrar la actividad
require_once '/includes/conexion.php';
require_once '/includes/helpers.php';

// Si hay un usuario logueado, registramos el cierre de sesión
if (isset($_SESSION['usuario'])) {
    registrarActividad(
        $conn,
        $_SESSION['usuario'],
        ACCION_LOGOUT
    );
}

// Limpiamos la sesión y la destruimos
$_SESSION = array();
session_destroy();

// Redirigimos al índice principal
header('Location: ../index.php');
exit();
