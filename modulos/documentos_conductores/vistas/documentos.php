<?php
// archivo: /modulos/documentos_conductores/vistas/documentos.php

require_once __DIR__ . '/../../../includes/header_panel.php';
require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';

if (!isset($_GET['id'])) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>ID de conductor no proporcionado.</div></div>";
    require_once __DIR__ . '/../../../includes/footer_panel.php';
    exit;
}

$idConductor = intval($_GET['id']);

// Cargar datos del conductor
require_once __DIR__ . '/../../../includes/config.php';
$conn = getConnection();

$sqlCon = "SELECT nombres, apellidos, dni FROM conductores WHERE id = $idConductor LIMIT 1";
$resCon = $conn->query($sqlCon);
$conductor = $resCon->fetch_assoc();
?>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Documentos del Conductor</h4>
    </div>

    <!-- Nombre visible -->
    <p class="fw-bold mb-2">
        Conductor:
        <span id="conductor_nombre">
            <?= htmlspecialchars($conductor['nombres'] . ' ' . $conductor['apellidos']) ?>
        </span>
        <span class="text-muted">(DNI: <?= htmlspecialchars($conductor['dni']) ?>)</span>
    </p>

    <!-- ID oculto para JS -->
    <input type="hidden" id="conductor_id" value="<?= $idConductor ?>">

    <table class="table table-bordered align-middle" id="tablaDocumentosConductor">
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
<?php require_once __DIR__ . '/../componentes/modal_historial.php'; ?>

<div id="notificacionesERP"></div>

<link rel="stylesheet" href="/modulos/documentos_conductores/css/documentos.css?v=<?= time() ?>">
<link rel="stylesheet" href="/modulos/documentos_conductores/css/notificaciones.css?v=<?= time() ?>">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ARCHIVOS MODULARIZADOS -->
<script src="/modulos/documentos_conductores/js/documentos.util.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_conductores/js/documentos.carga.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_conductores/js/documentos.modal.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_conductores/js/documentos.guardar.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_conductores/js/documentos.historial.js?v=<?= time() ?>"></script>
<script src="/modulos/documentos_conductores/js/documentos.main.js?v=<?= time() ?>"></script>

<?php require_once __DIR__ . '/../../../includes/footer_panel.php'; ?>
