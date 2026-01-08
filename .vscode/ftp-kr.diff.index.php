<?php
// archivo: /modulos/usuarios/index.php
// ----------------------------------------------
// Página principal del módulo de usuarios
// ----------------------------------------------
// Requiere sesión iniciada y permisos adecuados
// Incluye configuración, utilidades y conexión a BD
// Registra acceso en historial_bd
// ----------------------------------------------

require_once __DIR__ . '/../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
require_once INCLUDES_PATH . '/funciones.php';

$conn = getConnection();
requirePermiso('usuarios', 'ver');

registrarEnHistorial(
    $conn,
    $_SESSION['usuario'],
    "Visualizó lista de usuarios",
    "usuarios",
    $_SERVER['REMOTE_ADDR']
);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/head.php'; ?>
</head>

<body class="bg-light">

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/header_global.php'; ?>

    <!-- Contenedor principal del módulo -->
	<div class="container px-0 mt-1">
    
        <?php include __DIR__ . '/componentes/header.php'; ?>
        <?php include __DIR__ . '/componentes/tabs.php'; ?>
        <?php include __DIR__ . '/componentes/tabla.php'; ?>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/componentes/footer_global.php'; ?>

    <!-- Modal VER USUARIO -->
    <?php include __DIR__ . '/modales/modal_ver.php'; ?>

	<script src="js/usuarios.js"></script>
</body>
</html>