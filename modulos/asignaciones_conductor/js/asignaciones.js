// js/asignaciones.js

$(document).ready(function() {
  	$('#tablaActivas, #tablaHistorial').DataTable({
    	language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" }
  	});

  	var asignacionId;
  	$('.btn-finalizar').click(function(e) {
    	e.preventDefault();
    	asignacionId = $(this).data('finalizar-id');

		console.log("ID a finalizar:", asignacionId);


    	new bootstrap.Modal($('#confirmModal')).show();
  		});



  	$('#confirmBtn').click(function() {
    	window.location.href = "finalizar_asignacion.php?id=" + asignacionId;
  		});
	});
