<?php
	//	archivo	:	modulos/mantenimiento/entidades/views/ListView.php

	$isTrashView = empty($entidades['activos']) && !empty($entidades['inactivos']);
?>

<!-- Bootstrap y FontAwesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/modulos/mantenimiento/entidades/assets/css/entidades.css">

<div class="container">
	<h2>
		<i class="fa fa-building"></i> 
		<?= $isTrashView ? 'Entidades Eliminadas' : 'Entidades Locales' ?>
	</h2>

  	<div class="text-right" style="margin-bottom: 15px;">
    	<a href="/modulos/mantenimiento/mantenimiento.php" class="btn btn-default">
      		<i class="fa fa-arrow-left"></i> Volver al módulo de mantenimiento
    	</a>
    	<?php if (!$isTrashView): ?>
      		<button class="btn btn-info" data-toggle="modal" data-target="#modalNuevaEntidad">
        		<i class="fa fa-plus-circle"></i> Nueva entidad
      		</button>
    	<?php endif; ?>
  	</div>

  	<ul class="nav nav-tabs">
    	<?php if (!empty($entidades['activos'])): ?>
      		<li class="active"><a data-toggle="tab" href="#tab-activos">Activos <span class="badge"><?= count($entidades['activos']) ?></span></a></li>
      		<li><a data-toggle="tab" href="#tab-inactivos">Inactivos <span class="badge"><?= count($entidades['inactivos']) ?></span></a></li>
    	<?php elseif (!empty($entidades['inactivos'])): ?>
      		<li class="active"><a data-toggle="tab" href="#tab-inactivos">Eliminados <span class="badge"><?= count($entidades['inactivos']) ?></span></a></li>
    	<?php endif; ?>
  	</ul>

  	<div class="tab-content">
    	<?php foreach (['activos', 'inactivos'] as $estado): ?>
      		<?php if (!empty($entidades[$estado])): ?>
        		<div id="tab-<?= $estado ?>" class="tab-pane fade <?= $estado === 'activos' && !$isTrashView ? 'in active' : ($isTrashView && $estado === 'inactivos' ? 'in active' : '') ?>">
          			<?php foreach ($entidades[$estado] as $e): ?>
            			<div class="panel panel-default entidad-card <?= $estado === 'inactivos' ? 'inactive' : '' ?>">
            	  			<div class="panel-heading">
            	    			<strong><?= htmlspecialchars($e['nombre']) ?></strong>
            	    			<span class="pull-right text-muted">RUC: <?= htmlspecialchars($e['ruc']) ?></span>
            	  			</div>
            	  			<div class="panel-body">
            	    			<i class="fa fa-map-marker"></i> <?= htmlspecialchars($e['direccion']) ?><br>
           		     			Departamento: <?= isset($e['departamento']) ? htmlspecialchars($e['departamento']) : '<em>No definido</em>' ?>
            	  			</div>
            	  			<div class="panel-footer">
            	    			<button class="btn btn-xs btn-info" onclick="verEntidad(<?= $e['id'] ?>)">
            	    				<i class="fa fa-eye"></i> Ver
            	    			</button>
            	    			<?php if ($estado === 'activos'): ?>
            	    				<a href="?action=form&id=<?= $e['id'] ?>" class="btn btn-xs btn-primary">
										<i class="fa fa-pencil"></i> Editar
									</a>
            	    			  	<a href="?action=delete&id=<?= $e['id'] ?>" class="btn btn-xs btn-danger" onclick="return confirm('¿Eliminar esta entidad?')">
										<i class="fa fa-trash"></i> Eliminar
									</a>
            	    			<?php else: ?>
            	    			  	<a href="?action=restore&id=<?= $e['id'] ?>" class="btn btn-xs btn-success" onclick="return confirm('¿Restaurar esta entidad?')">
										<i class="fa fa-recycle"></i> Restaurar
									</a>
            	    			<?php endif; ?>
            	  			</div>
            			</div>
          			<?php endforeach; ?>
        		</div>
      		<?php endif; ?>
    	<?php endforeach; ?>
  	</div>

  	<?php include __DIR__ . '/FormNuevaEntidad.php'; ?>
</div>

<!-- Modal para ver entidad -->
<div class="modal fade" id="modalVerEntidad" tabindex="-1" role="dialog">
  	<div class="modal-dialog">
		<div class="modal-content" id="contenidoModalEntidad"></div>
	</div>
</div>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>

	function verEntidad(id) {
  		$('#contenidoModalEntidad').html('<div class="modal-body text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</div>');
  		$('#modalVerEntidad').modal('show');
  		$.get('/modulos/mantenimiento/entidades/controllers/VerEntidadModal.php', { id: id }, function(data) {
    		$('#contenidoModalEntidad').html(data);
  			});
		}

		$('#formNuevaEntidad').on('submit', function(e) {
  			e.preventDefault();
  			$.post('/modulos/mantenimiento/entidades/controllers/GuardarEntidad.php', $(this).serialize(), function(respuesta) {
    		if (respuesta === 'ok') {
      			$('#modalNuevaEntidad').modal('hide');
      			location.reload();
    			} 
			else {
      			alert('Error: ' + respuesta);
    			}
  			});
		});
</script>

<!-- ✅ Carga correcta del JS externo -->
<script src="/modulos/mantenimiento/entidades/assets/js/entidades.js"></script>

<script>
  // Bloque de limpieza controlada

  $('#modalNuevaEntidad').on('hidden.bs.modal', function () {
  // Limpia campos de texto
  $('#formNuevaEntidad input[type="text"]').val('');
  $('#formNuevaEntidad textarea').val('');

  // Restablece selects a valores por defecto
  $('#departamento').val('15'); // ID de Lima
  cargarProvincias('15');

  setTimeout(function () {
    $('#provincia').val('127'); // ID de provincia Lima
    cargarDistritos('127');

    setTimeout(function () {
      $('#distrito').val('1251'); // ID de distrito Lima
    }, 300);
  }, 300);
});
</script>
