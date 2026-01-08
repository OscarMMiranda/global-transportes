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

    <?php include __DIR__ . '/../../includes/componentes/head.php'; ?>

    <link rel="stylesheet" href="/modulos/usuarios/css/usuarios.css?v=1">
</head>

<body class="bg-light">
    <?php include __DIR__ . '/../../includes/componentes/header_global.php'; ?>
    <!-- Contenedor principal del módulo -->
    <div class="container px-0 mt-1">

        <!-- Header del módulo -->
        <?php include __DIR__ . '/componentes/header.php'; ?>

        <!-- Tabs del módulo -->
        <?php include __DIR__ . '/componentes/tabs.php'; ?>

        <!-- Tabla de usuarios -->
        <?php include __DIR__ . '/componentes/tabla.php'; ?>

        <!-- 🔥 MODALES (DEBEN IR ANTES DEL JS) -->
        <?php include __DIR__ . '/modales/modal_ver.php'; ?>
<?php include __DIR__ . '/modales/modal_crear.php'; ?>
<?php include __DIR__ . '/modales/modal_desactivar.php'; ?>
<?php include __DIR__ . '/modales/modal_eliminar.php'; ?>
<?php include __DIR__ . '/modales/modal_editar.php'; ?>

    </div>

    <!-- Footer global (carga Bootstrap, jQuery, DataTables, SweetAlert, etc.) -->
     <?php include __DIR__ . '/../../includes/componentes/footer_global.php'; ?>

    <!-- JS del módulo (se carga al final, cuando los modales YA existen) -->
     <script src="js/usuarios.js?v=<?php echo time(); ?>"></script>
    <!-- <script src="js/usuarios.js?v=1"></script>    -->

</body>
</html>