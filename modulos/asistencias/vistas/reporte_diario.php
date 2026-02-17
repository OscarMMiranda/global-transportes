<?php
    // archivo: modulos/asistencias/vistas/reporte_diario.php

    require_once __DIR__ . '/../../../includes/config.php';
    $conn = getConnection();

    require_once __DIR__ . '/../../../includes/header_panel.php';
    require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
    require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';

    require_once __DIR__ . '/../core/asistencia.func.php';
    require_once __DIR__ . '/../core/empresas.func.php';
    require_once __DIR__ . '/../core/conductores.func.php';
    require_once __DIR__ . '/../core/fechas.func.php';
    require_once __DIR__ . '/../core/helpers.func.php';

    $empresas = obtener_empresas($conn);
?>

<div class="card mt-1">

    <?php include __DIR__ . '/partes/header_reporte.php'; ?>

    <div class="card-body">

        <?php include __DIR__ . '/partes/filtros_reporte.php'; ?>

        <div id="contenedorReporteDiario"></div>

    </div>
</div>

<!-- ============================
     MODALES (fuera de contenedores)
============================= -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/modulos/asistencias/modales/modal_modificar_asistencia.php'; ?>
<?php include __DIR__ . '/../modales/modal_historial_asistencia.php'; ?>

<!-- ============================
     TOASTS
============================= -->
<?php include __DIR__ . '/partes/toast_success.php'; ?>
<?php include __DIR__ . '/partes/toast_error.php'; ?>
<?php include __DIR__ . '/partes/toast_warning.php'; ?>
<?php include __DIR__ . '/partes/toast_info.php'; ?>

<!-- ============================
     FOOTER (Bootstrap 5 aquí)
============================= -->
<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>

<!-- ============================
     SCRIPTS DEL MÓDULO (DEBEN IR DESPUÉS DEL FOOTER)
============================= -->
<?php include __DIR__ . '/partes/scripts_reporte.php'; ?>
