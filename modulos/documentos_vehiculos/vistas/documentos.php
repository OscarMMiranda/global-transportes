<?php
	// archivo: /modulos/documentos_vehiculos/vistas/documentos.php

	require_once __DIR__ . '/../../../includes/header_panel.php';
	require_once __DIR__ . '/../../../includes/componentes/topbar_global.php';
	require_once __DIR__ . '/../../../includes/componentes/navbar_global.php';

	/// Conexión
	require_once __DIR__ . '/../../../includes/config.php';
	$conn = getConnection();

	// Validar ID
	if (!isset($_GET['id'])) {
		echo "<div class='container mt-4'><div class='alert alert-danger'>ID de vehículo no proporcionado.</div></div>";
		require_once __DIR__ . '/../../../includes/footer_panel.php';
		exit;
		}

	$idVehiculo = intval($_GET['id']);

	// Cargar datos básicos del vehículo (solo columnas que sabemos que existen)
$sqlVeh = $conn->prepare("
    SELECT placa, anio
    FROM vehiculos
    WHERE id = ?
    LIMIT 1
");

$sqlVeh->bind_param("i", $idVehiculo);
$sqlVeh->execute();
$sqlVeh->bind_result($placa, $anio);
$sqlVeh->fetch();
$sqlVeh->close();


// Estado documental
require_once __DIR__ . '/../acciones/estado_documental.php';
$docOK = estadoDocumentalVehiculo($conn, $idVehiculo);

?>

<div class="container-fluid mt-4">

	<!-- Encabezado claro del vehículo -->
    <h4 class="mb-1">
        🚚 Vehículo: <?= htmlspecialchars($placa) ?> <?= $anio ? '— ' . htmlspecialchars($anio) : '' ?>
    </h4>

	<div class="mb-3" style="font-size:14px;">
        ID interno: <?= $idVehiculo ?> &nbsp;&nbsp; |

    	<?php if ($docOK): ?>
    		<span class="badge bg-success">Documentalmente habilitado</span>
    	<?php else: ?>
    	    <span class="badge bg-danger">Documentalmente deshabilitado</span>
    	<?php endif; ?>
    </div>

    <h5 class="mb-3">Documentos del Vehículo</h5>

    <!-- ID necesario para historial, carga, reemplazo -->
    <input type="hidden" id="vehiculo_id" value="<?= $idVehiculo ?>">

    <!-- TABLA PRINCIPAL -->
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

<!-- MODALES -->
<?php require_once __DIR__ . '/../componentes/modal_adjuntar_documento.php'; ?>
<?php require_once __DIR__ . '/../componentes/modal_preview.php'; ?>
<?php require_once __DIR__ . '/../componentes/modal_historial.php'; ?>

<div id="notificacionesERP"></div>

<!-- CSS -->
<link rel="stylesheet" href="/modulos/documentos_vehiculos/css/documentos.css?v=<?= time() ?>">
<link rel="stylesheet" href="/modulos/documentos_vehiculos/css/notificaciones.css?v=<?= time() ?>">

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Carga documentos, adjuntar, reemplazar, preview -->
<script src="/modulos/documentos_vehiculos/js/documentos.js?v=<?= time() ?>"></script>

<!-- Historial (abrir, cargar, cerrar) -->
<script src="/modulos/documentos_vehiculos/js/documentos_historial.js?v=<?= time() ?>"></script>

<?php require_once __DIR__ . '/../../../includes/footer_panel.php'; ?>
