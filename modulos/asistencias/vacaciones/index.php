<?php
// ============================================================
// SUBMÓDULO: VACACIONES
// archivo: /modulos/asistencias/vacaciones/index.php
// ============================================================

require_once __DIR__ . '/../../../includes/config.php';

// Vista seleccionada
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'saldos';

// Validar vistas permitidas
$vistas_permitidas = [
    'saldos',
    'periodos',
    'solicitudes',
    'movimientos',
    'calendario'
];

if (!in_array($vista, $vistas_permitidas)) {
    $vista = 'saldos';
}

// Rutas base del módulo
$base = "/modulos/asistencias/vacaciones";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vacaciones</title>

    <!-- Bootstrap / FontAwesome / Estilos globales -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Estilos propios del módulo -->
    <link rel="stylesheet" href="<?= $base ?>/css/vacaciones.css">
</head>

<body>

<div class="container-fluid mt-3">

    <!-- ===========================
         HEADER CORPORATIVO
    ============================ -->
    <?php include __DIR__ . '/componentes/header/header.php'; ?>

    <!-- ===========================
         FILTROS
    ============================ -->
    <?php include __DIR__ . '/componentes/header/header_filtros.php'; ?>

    <!-- ===========================
         ACCIONES (botones)
    ============================ -->
    <?php include __DIR__ . '/componentes/header/header_actions.php'; ?>

    <hr>

    <!-- ===========================
         CONTENIDO DINÁMICO
    ============================ -->
    <div id="contenedor_vista">

        <?php
        switch ($vista) {

            case 'saldos':
                include __DIR__ . '/vistas/vista_saldos.php';
                break;

            case 'periodos':
                include __DIR__ . '/vistas/vista_periodos.php';
                break;

            case 'solicitudes':
                include __DIR__ . '/vistas/vista_solicitudes.php';
                break;

            case 'movimientos':
                include __DIR__ . '/vistas/vista_movimientos.php';
                break;

            case 'calendario':
                include __DIR__ . '/vistas/vista_calendario.php';
                break;
        }
        ?>

    </div>

</div>

<!-- ===========================
     MODALS GLOBALES
=========================== -->
<?php
include __DIR__ . '/componentes/modals/modal_solicitar_vacaciones.php';
include __DIR__ . '/componentes/modals/modal_aprobar_vacaciones.php';
include __DIR__ . '/componentes/modals/modal_detalle_periodo.php';
include __DIR__ . '/componentes/modals/modal_movimientos.php';
?>

<!-- ===========================
     JS DEL MÓDULO
=========================== -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script src="<?= $base ?>/js/vacaciones_saldos.js"></script>
<script src="<?= $base ?>/js/vacaciones_periodos.js"></script>
<script src="<?= $base ?>/js/vacaciones_solicitudes.js"></script>
<script src="<?= $base ?>/js/vacaciones_movimientos.js"></script>
<script src="<?= $base ?>/js/vacaciones_calendario.js"></script>
<script src="<?= $base ?>/js/vacaciones_modal.js"></script>


</body>
</html>
