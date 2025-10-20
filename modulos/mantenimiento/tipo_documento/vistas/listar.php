<?php
	// archivo: /modulos/mantenimiento/tipo_documento/vistas/listar.php

	require_once __DIR__ . '/../controllers/tipo_documento_controller.php';

	// Cargar datos base
	$estado     = isset($_GET['estado']) ? (int)$_GET['estado'] : 1;
	$registro   = isset($_GET['id']) ? obtenerTipoDocumento($_GET['id']) : null;
	$categorias = listarCategoriasDocumento();
?>

<!DOCTYPE html>
<html lang="es">
	<head>
  		<meta charset="UTF-8">
  		<title>Tipos de Documento</title>
  		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

		<link rel="stylesheet" href="css/estilos.css">
	</head>

	<body class="bg-light p-4">
  		<div class="container">
    		<h1 class="mb-4 text-primary">
      			<i class="fas fa-file-alt me-2 text-secondary"></i> Tipos de Documento
    		</h1>

    	<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
      		<!-- Botón Volver -->
      		<a href="../mantenimiento.php" class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center shadow-sm">
        		<i class="fas fa-arrow-left me-2"></i>
        		<span>Volver a Mantenimiento</span>
      		</a>

      		<!-- Botón Nuevo -->
      		<button type="button" class="btn btn-success btn-sm d-flex align-items-center justify-content-center shadow-sm"
            	  	data-bs-toggle="modal" data-bs-target="#modalTipoDocumento">
        		<i class="fas fa-plus me-2"></i>
        		<span>Nuevo Tipo de Documento</span>
      		</button>
    	</div>

    	<!-- Mensajes flash -->
    	<?php if (isset($_SESSION['msg'])): ?>
      		<div class="alert alert-success"><?= htmlspecialchars($_SESSION['msg']) ?></div>
      		<?php unset($_SESSION['msg']); ?>
    	<?php endif; ?>

    	<!-- Botones de filtro -->
    	<div class="btn-group mb-3">
      		<button id="btn-activos" class="btn btn-success btn-sm">🟢 Activos</button>
      		<button id="btn-inactivos" class="btn btn-secondary btn-sm">⚪ Inactivos</button>
    	</div>

    	<!-- Contenedor de tabla -->
    	<div id="contenedor-tabla-tipos"></div>
    		<!-- Modal -->
    		<?php include __DIR__ . '/../modales/modal_tipo_documento.php'; ?>
  		</div>

  		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  		<script>
    		function cargarTipos(url) {
      			$.ajax({
        		url: url,
        		method: 'POST',
        		dataType: 'json',
        		success: function(data) {
					$.post('/modulos/mantenimiento/tipo_documento/componentes/tabla_tipos_documento.php', { tipos: JSON.stringify(data) }, function(html) {
            		$('#contenedor-tabla-tipos').html(html);
          			});
        		},
        		error: function() {
          			$('#contenedor-tabla-tipos').html("<div class='alert alert-danger text-center'>❌ Error al cargar los tipos de documento.</div>");
        			}
      			});
    		}
  			$('#btn-activos').click(() => cargarTipos('/modulos/mantenimiento/tipo_documento/ajax/listar_activos.php'));
			$('#btn-inactivos').click(() => cargarTipos('/modulos/mantenimiento/tipo_documento/ajax/listar_inactivos.php'));
			$(document).ready(() => cargarTipos('/modulos/mantenimiento/tipo_documento/ajax/listar_activos.php'));

    		<?php if ($registro && $registro['id'] > 0): ?>
      			const modal = new bootstrap.Modal(document.getElementById('modalTipoDocumento'));
      			modal.show();
    		<?php endif; ?>
  		</script>

  		<!-- Acciones AJAX para activar/desactivar -->
		<script src="/modulos/mantenimiento/tipo_documento/js/acciones.js"></script>
	</body>
</html>