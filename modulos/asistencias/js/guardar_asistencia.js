/*
    archivo: /modulos/asistencias/js/guardar_asistencia.js
    módulo: asistencias
*/

console.log("guardar_asistencia.js CARGADO");

$(document).on("click", "#btnGuardarCambiosAsistencia", function () {

    let payload = {
        asistencia_id: $("#asistencia_id").val(),
        empresa_id: $("#empresa_id_hidden").val(),
        conductor_id: $("#conductor_id_hidden").val(),
        codigo_tipo: $("#codigo_tipo_edit").val(),
        fecha: $("#fecha_edit").val(),
        hora_entrada: $("#hora_entrada_edit").val(),
        hora_salida: $("#hora_salida_edit").val(),
        observacion: $("#observacion_edit").val()
    };

    $.post('../acciones/modificar.php', payload, function (r) {

    	if (!r.ok) {
    		alert("Error: " + (r.error || "No se pudo guardar"));
    		return;
			}

		// Mostrar mensaje elegante de éxito
var toastEl = document.getElementById('toastSuccess');
var toast = new bootstrap.Toast(toastEl);
toast.show();


        let modalEl = document.getElementById('modalModificarAsistencia');
        let modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

		if (typeof $.fn.DataTable !== "undefined") {
			if ($.fn.DataTable.isDataTable('#tablaAsistencias')) {
        		$('#tablaAsistencias').DataTable().ajax.reload(null, false);
				return;
    			}
			}


        if (typeof cargarReporte === "function") {
            cargarReporte();
            return;
        }

        location.reload();

    }, 'json')
    .fail(function (xhr) {
        alert("Error de comunicación con el servidor.");
        console.log("ERROR AJAX:", xhr.responseText);
    });
});

