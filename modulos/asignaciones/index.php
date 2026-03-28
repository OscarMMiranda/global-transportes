<?php
// modulos/asignaciones/index.php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Configuración global
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers.php';

// Conexión
$conn = getConnection();

// Sesión y trazabilidad
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['usuario'])) {
    registrarActividad(
        $conn,
        $_SESSION['usuario'],
        'Accedió al módulo de Asignaciones'
    );
}

// Header del módulo (NO el global)
require_once __DIR__ . '/componentes/header_asignaciones.php';
?>

<!-- Título -->
<main class="col py-4">

    <h3 class="mb-4">Asignaciones – Conductores / Tractos / Carretas</h3>

    <!-- Botón para abrir modal -->
    <button class="btn btn-success mb-3 shadow-sm"
            data-toggle="modal"
            data-target="#modalAsignar">
        <i class="fas fa-plus me-1"></i> Nueva asignación
    </button>

    <!-- Filtros -->
    <?php include __DIR__ . '/componentes/filtros.php'; ?>

    <!-- Tabla -->
    <?php include __DIR__ . '/componentes/tabla_asignaciones.php'; ?>

</main>

<!-- Modales -->

<?php include __DIR__ . '/modales/modal_asignacion.php'; ?>
<?php include __DIR__ . '/modales/modal_finalizar.php'; ?>
<?php include __DIR__ . '/modales/modal_detalle.php'; ?>
<?php include __DIR__ . '/modales/modal_editar.php'; ?>
<?php include __DIR__ . '/modales/modal_reasignar.php'; ?>


<?php
// Footer del módulo (NO el global)
require_once __DIR__ . '/componentes/footer_asignaciones.php';
?>
