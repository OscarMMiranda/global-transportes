<?php
// archivo: /modulos/usuarios/index.php
// ----------------------------------------------
// Módulo: Usuarios
// Acción: Visualizar listado
// Requisitos: Sesión activa + permiso usuarios.ver
// ----------------------------------------------

require_once __DIR__ . '/../../includes/config.php';
require_once INCLUDES_PATH . '/permisos.php';
require_once INCLUDES_PATH . '/funciones.php';

// Validación de sesión (si no la hace config.php)
if (!isset($_SESSION['usuario'])) {
    header("Location: /login.php");
    exit;
}

$conn = getConnection();

// Validación de permiso
requirePermiso('usuarios', 'ver');

// Registro en historial (no debe romper la vista si falla)
try {
    registrarEnHistorial(
        $conn,
        $_SESSION['usuario'],
        "Visualizó lista de usuarios",
        "usuarios",
        $_SERVER['REMOTE_ADDR']
    );
} catch (Exception $e) {
    // log interno opcional
}

?>
<!DOCTYPE html>
<html lang="es">
<head>

    <?php include __DIR__ . '/../../includes/componentes/head.php'; ?>

    <link rel="stylesheet" href="/modulos/usuarios/css/usuarios.css?v=1">

</head>

<body class="bg-light">

    <?php include __DIR__ . '/../../includes/componentes/header_global.php'; ?>

    <div id="modulo-usuarios" class="container px-0 mt-1">

        <?php include __DIR__ . '/componentes/header.php'; ?>
        <?php include __DIR__ . '/componentes/tabs.php'; ?>
        <?php include __DIR__ . '/componentes/tabla.php'; ?>

        <!-- Modales -->
        <?php include __DIR__ . '/modales/modal_ver.php'; ?>
        <?php include __DIR__ . '/modales/modal_crear.php'; ?>
        <?php include __DIR__ . '/modales/modal_desactivar.php'; ?>
        <?php include __DIR__ . '/modales/modal_eliminar.php'; ?>
        <?php include __DIR__ . '/modales/modal_editar.php'; ?>

    </div>

    <?php include __DIR__ . '/../../includes/componentes/footer_global.php'; ?>

    <script src="js/usuarios.js?v=<?php echo time(); ?>"></script>

</body>
</html>