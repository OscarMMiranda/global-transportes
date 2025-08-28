$(document).ready(function () {
  // 1) Inicializar DataTables con idioma local
  const tablaActivas = $('#tablaActivas').DataTable({
    language: { url: '/modulos/asignaciones_conductor/es-ES.json' }
  });

  let tablaHistorial = $('#tablaHistorial').DataTable({
    language: { url: '/modulos/asignaciones_conductor/es-ES.json' }
  });

  	// 2) Inicializar tooltips
  	$('[data-bs-toggle="tooltip"]').tooltip();

	// 3) Variable global para ID de asignación
	let asignacionId = null;

	// 4) Abrir modal de confirmación
  	$('.btn-finalizar').on('click', function (e) {
    	e.preventDefault();
    	asignacionId = $(this).data('finalizar-id');
    	console.log('🟡 ID a finalizar:', asignacionId);
    	if (!asignacionId || isNaN(asignacionId)) return;
    	new bootstrap.Modal($('#confirmModal')[0]).show();
  		});

	$('.btn-editar').on('click', function () {
  		const id = $(this).data('id');
  		if (!id) return;

  		// Redirigir al formulario de edición
  		window.location.href = `/modulos/asignaciones_conductor/index.php?action=edit&id=${id}`;
		});

	
	$('.btn-cancelar').on('click', function () {
  		const id = $(this).data('id');
  		if (!id) return;

  		// Confirmar acción
  		if (!confirm('¿Estás seguro de que deseas cancelar esta asignación?')) return;

  		$.ajax({
    		url: '/modulos/asignaciones_conductor/acciones/cancelar.php',
    		method: 'POST',
    		data: { asignacion_id: id },
    		dataType: 'json',
    	success: function (response) {
      	if (response.success) {
        	alert(response.message);
        	location.reload(); // o recarga parcial
      		} 
		else {
        	alert('Error: ' + response.message);
      		}
    	},
    	error: function (xhr, status, error) {
    console.error('🔴 AJAX error:', error);
    alert('Error en la solicitud AJAX.');
    	}
  	});
});



	// 5) Confirmar finalización vía AJAX
	$('#confirmBtn').on('click', function () {
    	if (!asignacionId) return;

    	$('#confirmBtn').prop('disabled', true)
      		.html('<i class="fas fa-spinner fa-spin"></i> Finalizando...');

		$.ajax({
			url: '/modulos/asignaciones_conductor/finalizar_asignacion.php',
      		method: 'POST',
      		data: { asignacion_id: asignacionId },
      		dataType: 'json',
      		success: function (response) {
        		$('#confirmBtn').prop('disabled', false).html('Sí, finalizar');
        		if (!response.success) {
          			alert('Error: ' + response.message);
          			return;
        		}

        	// Ocultar modal y mostrar alerta
        	bootstrap.Modal.getInstance($('#confirmModal')[0]).hide();
        	$('.container').prepend(`
          		<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            		<i class="fas fa-check-circle me-2"></i>
            		${response.message}
            		<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          		</div>
        	`);


        // Quitar fila de asignaciones activas
        const fila = $(`button[data-finalizar-id="${asignacionId}"]`).closest('tr');
        tablaActivas.row(fila).remove().draw();

	// Recargar historial con fadeIn en la nueva fila
        $('#historialContainer').load('/modulos/asignaciones_conductor/vistas/tabla_historial.php', function() {
  		
			const nuevaFila = $('#tablaHistorial tbody tr').first();
            nuevaFila.hide().fadeIn(600);
		
		if ($.fn.DataTable.isDataTable('#tablaHistorial')) {
    		tablaHistorial.destroy();
  			}
  		tablaHistorial = $('#tablaHistorial').DataTable({
    		language: { url: '/modulos/asignaciones_conductor/es-ES.json' }
  			});
		});

        asignacionId = null;
      },
      error: function (xhr, status, error) {
        $('#confirmBtn').prop('disabled', false).html('Sí, finalizar');
        console.error('🔴 Error AJAX:', error);
        alert('Error en la solicitud AJAX.');
      }
    });
  });
});