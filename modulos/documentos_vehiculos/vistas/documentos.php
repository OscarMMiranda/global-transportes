<?php
// archivo: /modulos/documentos_vehiculos/vistas/documentos.php

require_once __DIR__ . '/../../../includes/header_panel.php';
require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';

if (!isset($_GET['id'])) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ID de vehículo no proporcionado.</div></div>";
    require_once __DIR__ . '/../../../includes/footer_panel.php';
    exit;
}

$idVehiculo = intval($_GET['id']);
?>

<div class="container-fluid mt-4">
    <h4 class="mb-3">Documentos del Vehículo</h4>

    <input type="hidden" id="vehiculo_id" value="<?= $idVehiculo ?>">

    <table class="table table-bordered align-middle" id="tablaDocumentosVehiculo">
        <thead class="table-light">
            <tr>
                <th>Estado</th>
                <th>Documento</th>
                <th>Vencimiento</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="4" class="text-center text-muted">Cargando…</td></tr>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../componentes/modal_adjuntar_documento.php'; ?>
<?php require_once __DIR__ . '/../componentes/modal_preview.php'; ?>
<div id="notificacionesERP"></div>


<link rel="stylesheet" href="/modulos/documentos_vehiculos/css/documentos.css?v=<?= time() ?>">
<link rel="stylesheet" href="/modulos/documentos_vehiculos/css/notificaciones.css?v=<?= time() ?>">


<!-- jQuery debe ir ANTES del JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="/modulos/documentos_vehiculos/js/documentos.js?v=<?= time() ?>"></script>

    <?php require_once __DIR__ . '/../../../includes/footer_panel.php'; ?>
