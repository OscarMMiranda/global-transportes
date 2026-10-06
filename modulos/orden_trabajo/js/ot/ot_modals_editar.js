// ======================================================
// JS: ot_modals_editar.js
// RESPONSABILIDAD: Abrir, cargar y guardar OT
// GLOBAL 2026
// PHP 5.6 Compatible
// ======================================================



// UTILIDAD FECHA
function convertirFechaISO(fecha)
{
    if (!fecha) {
        return '';
    }

    if (fecha.indexOf('/') === -1) {
        return fecha;
    }

    var partes = fecha.split('/');

    return partes[2] + '-' +
           partes[1] + '-' +
           partes[0];
}


// ABRIR MODAL EDITAR
function editarOT(id)
{
    if (!id) {
        return;
    }

	console.log('EDITAR OT');
	console.log(id);

    $("#modalEditarOT").modal("show");

    $.ajax({
        url	: "/modulos/orden_trabajo/api/ot/ot_editar_api.php",
		type: "POST",
		dataType: "json",
		data: {
			id: id
			},

        success: function(r)
        {
            console.log('RESPUESTA EDITAR');
            console.log(r);

            if (!r.ok) {
				alert(r.msg);
                return;
				}

            var d = r.data;

            console.log('DATA OT');
            console.table(d);

            // CAMPOS BASE

            $('#editar_id').val(d.id || '');
            $('#editar_numero_ot').val(
                d.numero_ot || ''
            );
            $('#editar_fecha').val(
                d.fecha || ''
            );
            $('#editar_semana_ot').val(
                d.semana_ot || ''
            );
            $('#editar_oc_cliente').val(
                d.oc_cliente || ''
            );
            $('#editar_numero_dam').val(
                d.numero_dam || ''
            );

            $('#editar_numero_booking').val(
                d.numero_booking || ''
            );

            $('#editar_otros').val(
                d.otros || ''
            );

            // =====================================
            // COMBOS
            // =====================================

            if (
                typeof cargarSelect === 'function'
            ) {

                cargarSelect(
                    '#editar_cliente_id',
                    r.clientes,
                    d.cliente_id
                );

                cargarSelect(
                    '#editar_empresa_id',
                    r.empresas,
                    d.empresa_id
                );

                cargarSelect(
                    '#editar_tipo_ot',
                    r.tipos_ot,
                    d.tipo_ot_id
                );
            }

            // =====================================
			// CAMPOS DINÁMICOS
			// =====================================

			mostrarCamposEditarPorId(
    			d.tipo_ot_id
				);

			$('#editar_tipo_ot')
    		.off('change')
    		.on('change', function () {

        		mostrarCamposEditarPorId(
            	$(this).val()
        	);

    		});
        },

        error: function(xhr)
        {
            console.log(xhr);

            alert(
                'Error al obtener los datos de la OT'
            );
        }
    });
}



// ======================================================
// GUARDAR EDICIÓN
// ======================================================
function guardarEditarOT()
{
    if (typeof limpiarMensajes === 'function') {
        limpiarMensajes('#msgEditarOT');
    }

    if (typeof loaderOn === 'function') {
        loaderOn('loaderEditarOT');
    }

	var formData = {

    id: $('#editar_id').val(),

    numero_ot: $('#editar_numero_ot').val(),

    fecha: $('#editar_fecha').val(),

    oc_cliente: $('#editar_oc_cliente').val(),

    cliente_id: $('#editar_cliente_id').val(),

    empresa_id: $('#editar_empresa_id').val(),

    tipo_ot_id: $('#editar_tipo_ot').val(),

    numero_dam: $('#editar_numero_dam').val(),

    numero_booking: $('#editar_numero_booking').val(),

    otros: $('#editar_otros').val()

};

    $.ajax({

        url: '/modulos/orden_trabajo/api/ot/ot_editar_guardar_api.php',
        type: 'POST',
        dataType: 'json',
        data: formData,

       success: function(r)
{
    console.log('RESPUESTA GUARDAR');
    console.log(r);

    if (typeof loaderOff === 'function') {
        loaderOff('loaderEditarOT');
    }

    if (!r.ok)
    {
        console.log('ERROR API');
        console.log(r);

        alert(r.msg || 'Error');

        return;
    }

    // alert('OT actualizada correctamente');

    // $('#modalEditarOT').modal('hide');

	Swal.fire({
    icon: 'success',
    title: 'OT actualizada',
    text: 'La Orden de Trabajo fue actualizada correctamente.',
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#0d6efd',
    allowOutsideClick: false
}).then(function() {

    $('#modalEditarOT').modal('hide');

    if (
        typeof tablaOT !== 'undefined' &&
        tablaOT !== null
    ) {
        tablaOT.ajax.reload(
            null,
            false
        );
    }

});

    if (
        typeof tablaOT !== 'undefined' &&
        tablaOT !== null
    ) {
        tablaOT.ajax.reload(
            null,
            false
        );
    }
},

        error: function(xhr)
{
    console.log('STATUS');
    console.log(xhr.status);

    console.log('RESPONSE');
    console.log(xhr.responseText);

    if (typeof loaderOff === 'function') {
        loaderOff('loaderEditarOT');
    }

    alert('Error AJAX');
}
    });
}



// ======================================================
// CAMPOS DINÁMICOS SEGÚN TIPO OT
// ======================================================
function mostrarCamposEditarPorId(tipo_ot_id)
{
    $('#campo_importacion').addClass('d-none');
    $('#campo_exportacion').addClass('d-none');
    $('#campo_nacional').addClass('d-none');

    tipo_ot_id = parseInt(tipo_ot_id, 10);

    switch (tipo_ot_id)
    {
        case 1: // NACIONAL

            $('#campo_nacional')
                .removeClass('d-none');

            break;

        case 2: // IMPORTACION

            $('#campo_importacion')
                .removeClass('d-none');

            break;

        case 3: // EXPORTACION

            $('#campo_exportacion')
                .removeClass('d-none');

            break;
    }
}

// ======================================================
// BOTON GUARDAR CAMBIOS
// ======================================================

$(document).on(
    'click',
    '#ot-btn-guardar-editar',
    function ()
    {
        console.log('CLICK GUARDAR EDITAR');

        guardarEditarOT();
    }
);