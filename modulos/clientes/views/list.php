<?php
	//	archivo	:	/modulos/clientes/views/list.php
?>

<link 
	rel="stylesheet" 
	href="<?= BASE_URL ?>assets/css/clientes.css"
>

<!-- DataTables CSS -->
<link 
	rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"
>

<!-- DataTables JS + Bootstrap wrapper -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js" defer></script>


<script>
  // Variable global que tu JS puede leer
  window.CLIENTES_API_URL = '<?= BASE_URL ?>modulos/clientes/controllers/ApiController.php';
</script>

<script 
	src="<?= BASE_URL ?>assets/js/clientes.js" 
	defer>
</script>




<main class="container mt-4">
	<h2 class="text-center mb-4">
  		<span class="d-inline-flex align-items-center gap-2 titulo-pagina">
    		<i class="fas fa-address-book text-primary fs-1"></i>
    		<span class="text-dark">Gestión de Clientes</span>
  		</span>
	</h2>

  	<!-- <div class="d-flex align-items-center justify-content-between mb-3"> -->
	<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">		
    	<div class="btn-group">
      		<!-- Botón Registrar -->
			<a 
				href="?action=form" 
				class="btn btn-outline-success ms-2"
				style="width: 200px; height: 35px;"
			>	
        		<i class="fas fa-user-plus me-1"></i> 
				Registrar Cliente
      		</a>

			<!-- Botón Volver -->
      		<a href="<?= BASE_URL ?>modulos/erp_dashboard.php" 
				class="btn btn-outline-secondary ms-2"
				style="width: 200px; height: 35px;"
			>
        		<i class="fas fa-arrow-left me-1"></i> 
				Volver al Dashboard
      		</a>

			<!-- Botón Ver eliminados -->
			<a href="?action=trash" 
				class="btn btn-outline-danger ms-2"
				style="width: 200px; height: 35px;"
			>
				<i class="fas fa-trash-alt me-1"></i>
  				Ver Eliminados
			</a>

	<!-- <div class="mb-3">
		<input
  			type="text"
  			id="busquedaClientes"
  			class="form-control mb-3 ms-2"
  			placeholder="🔍 Buscar ..."
		>
	</div> -->
</div>
		
  	</div>

  	<?php if (! empty($_GET['msg'])): ?>
    	<div class="alert alert-<?= $_GET['msg'] === 'ok' ? 'success' : 'danger' ?>">
    		<?= $_GET['msg'] === 'ok'
        		? 'Operación exitosa.'
        		: 'Ocurrió un error.' ?>
    	</div>
  	<?php endif; ?>

  	<div 
		class="table-responsive"
		style="max-height: 500px; overflow-y: auto;"
	>
    	<table 
			id="tablaClientes" 
			class="table table-hover table-striped align-middle shadow-sm small"
			style="width:100%"
		>
      		<thead class="table-dark sticky-top">
        		<tr>
          			<th>ID</th>
          			<th>Nombre</th>
          			<th>RUC</th>
          			<th>Dirección</th>
          			<th>Distrito</th>
          			<th class="text-center">Acciones</th>
        		</tr>
      		</thead>

			<!-- <tfoot>
    			<tr>
      				<th><input type="text" placeholder="ID" /></th>
      				<th><input type="text" placeholder="Nombre" /></th>
      				<th><input type="text" placeholder="RUC" /></th>
      				<th><input type="text" placeholder="Dirección" /></th>
      				<th><input type="text" placeholder="Distrito" /></th>
      				<th></th>
    			</tr>
  			</tfoot> -->

      		<tbody>
        		<?php if (count($clientes)): ?>
          		<?php foreach ($clientes as $c): ?>
            	<tr>
              		<td><?= $c['id'] ?></td>
              		<td><?= htmlspecialchars($c['nombre'], ENT_QUOTES) ?></td>
              		<td><?= htmlspecialchars($c['ruc'], ENT_QUOTES) ?></td>
              		<td><?= htmlspecialchars($c['direccion'], ENT_QUOTES) ?></td>
              		<td><?= htmlspecialchars($c['distrito'], ENT_QUOTES) ?></td>
              		<td class="text-center">
                		<a
                  			href="?action=form&id=<?= $c['id'] ?>"
                  			class="btn btn-sm btn-warning me-1"
                  			title="Editar cliente"
                		>
							<i class="fas fa-edit"></i>
						</a>

                		<button
                  			type="button"
                  			class="btn btn-sm btn-info btn-ver me-1"
                  			data-id="<?= $c['id'] ?>"
                  			title="Ver detalles"
                		>
							<i class="fas fa-eye"></i>
						</button>

                		<a
                  			href="?action=delete&id=<?= $c['id'] ?>"
                  			class="btn btn-sm btn-danger"
                  			onclick="return confirm('¿Eliminar este cliente?');"
                  			title="Eliminar"
                		>
							<i class="fas fa-trash-alt"></i>
						</a>
              		</td>
            	</tr>
          		<?php endforeach; ?>
        		<?php else: ?>
          			<tr>
            			<td colspan="6" class="text-center text-muted">
              				No hay clientes activos.
            			</td>
          			</tr>
        		<?php endif; ?>
      		</tbody>
    	</table>
  	</div>
</main>

<!-- Modal Ver Cliente -->
<div 
	class="modal fade" 
	id="modalVerCliente" 
	tabindex="-1" 
	aria-hidden="true"
>
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title">👁️ Detalle del Cliente</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      		</div>
      		<div class="modal-body">
        	<!-- Aquí inyectaremos el contenido vía AJAX -->
      		</div>
    	</div>
  	</div>
</div>

<!-- jQuery (requisito DataTables) -->
<script
  src="https://code.jquery.com/jquery-3.7.0.min.js"
  integrity="sha256-..."
  crossorigin="anonymous"
  defer
></script>

<!-- DataTables JS -->
<script
  src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"
  defer
></script>
<script
  src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"
  defer
></script>

<!-- Tu JS: Api URL + inicialización + modal + buscador -->
<script src="<?= BASE_URL ?>assets/js/clientes.js" defer></script>

