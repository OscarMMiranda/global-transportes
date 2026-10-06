// ======================================================
//  JS: ot_modals_nueva.js
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Abrir e inicializar Nueva OT
//  GLOBAL 2026 — ARCHIVO OFICIAL
// ======================================================

// ------------------------------------------------------
// Abrir modal Nueva OT
// ------------------------------------------------------
function abrirNuevaOT() {

    console.log('[OT] → abrirNuevaOT() ejecutado');

    // Limpiar formulario
    $('#ot-form-crear')[0].reset();


	// Ocultar campo dinámico
$('#crear_campo_dinamico')
    .addClass('d-none')
    .hide();

$('#crear_campo_dinamico_label')
    .text('');

$('#crear_campo_dinamico_input')
    .val('');

    // Inicializar datos
    inicializarNuevaOT();

    // Mostrar modal
    $('#modal-nueva-ot').modal('show');
}

// ------------------------------------------------------
// Inicializar formulario
// ------------------------------------------------------
function inicializarNuevaOT() {

    cargarFechaActual();
    cargarSiguienteOT();
    cargarClientes();
    cargarEmpresas();
    cargarTiposOT();
}

// ------------------------------------------------------
// Fecha actual
// ------------------------------------------------------
function cargarFechaActual() {

    var hoy = new Date();

    var yyyy = hoy.getFullYear();
    var mm   = ('0' + (hoy.getMonth() + 1)).slice(-2);
    var dd   = ('0' + hoy.getDate()).slice(-2);

    $('#crear_fecha').val(
        yyyy + '-' + mm + '-' + dd
    );
}

// ------------------------------------------------------
// Siguiente número OT
// ------------------------------------------------------
function cargarSiguienteOT() {

    var anio = new Date().getFullYear();

    $.ajax({

        url: '/modulos/orden_trabajo/api/ot/ot_siguiente_numero_api.php',
        type: 'POST',
        dataType: 'json',

        data: {
            anio: anio
        },

        success: function (resp) {

            if (
                resp &&
                resp.ok &&
                resp.siguiente_ot
            ) {

                $('#crear_numero_ot').val(
                    resp.siguiente_ot
                );

            } else {

                $('#crear_numero_ot').val(
                    '0001-' + anio
                );
            }
        },

        error: function () {

            $('#crear_numero_ot').val(
                '0001-' + anio
            );
        }

    });
}

// ------------------------------------------------------
// Clientes
// ------------------------------------------------------
function cargarClientes() {

    $.getJSON(
        '/modulos/orden_trabajo/api/catalogos/cliente_listado_api.php',
        function (resp) {

            var html =
                '<option value="">Seleccione...</option>';

            if (resp.ok) {

                $.each(resp.data, function (i, item) {

                    html +=
                        '<option value="' +
                        item.id +
                        '">' +
                        item.nombre +
                        '</option>';
                });
            }

            $('#crear_cliente_id').html(html);
        }
    );
}

// ------------------------------------------------------
// Empresas
// ------------------------------------------------------
function cargarEmpresas() {

    $.getJSON(
        '/modulos/orden_trabajo/api/catalogos/empresa_listado_api.php',
        function (resp) {

            var html =
                '<option value="">Seleccione...</option>';

            if (resp.ok) {

                $.each(resp.data, function (i, item) {

                    html +=
                        '<option value="' +
                        item.id +
                        '">' +
                        item.razon_social +
                        '</option>';
                });
            }

            $('#crear_empresa_id').html(html);
        }
    );
}

	// ------------------------------------------------------
	// Tipos OT
	// ------------------------------------------------------
	function cargarTiposOT() 
		{
    	$.getJSON(
        '/modulos/orden_trabajo/api/catalogos/tipo_ot_listado_api.php',
        function (resp) 
			{
            var html =
            '<option value="">Seleccione...</option>';

            if (resp.ok) 
				{
                $.each(resp.data, function (i, item) 
					{
                    html +=
                        '<option value="' +
                        item.id +
                        '">' +
                        item.nombre +
                        '</option>';
                	});
            	}

            	$('#crear_tipo_ot_id').html(html);
        	}
    		);
		}

	// ------------------------------------------------------
	// CAMPO DINÁMICO SEGÚN TIPO OT
	// ------------------------------------------------------
	$(document).on('change', '#crear_tipo_ot_id', function () 
		{
    	var tipo = $(this).val();
		console.log('TIPO OT:', tipo);

    	// Ocultar inicialmente
    	$('#crear_campo_dinamico')
        	.addClass('d-none')
			.hide();
    	$('#crear_campo_dinamico_input').val('');

		// IMPORTACION
    	if (tipo === '2') 
			{
        	$('#crear_campo_dinamico_label')
            	.text('D.U.A. / D.A.M.');
        	$('#crear_campo_dinamico')
            	.removeClass('d-none')
            	.show();
        	return;
    		}

    	// EXPORTACION
    	if (tipo === '3') 
			{
			$('#crear_campo_dinamico_label')
            	.text('BOOKING');
			$('#crear_campo_dinamico')
				.removeClass('d-none')
				.show();

			return;
			}

		// NACIONAL
		if (tipo === '1') 
			{
        	$('#crear_campo_dinamico_label')
            	.text('OTROS');
			$('#crear_campo_dinamico')
				.removeClass('d-none')
				.show();
			return;
    		}
		});

// ======================================================
// VALIDAR NUEVA OT
// ======================================================

	function validarNuevaOT() 
		{
		if (!$('#crear_cliente_id').val()) 
			{
	        Swal.fire({
	            icon: 'warning',
	            title: 'Cliente requerido',
	            text: 'Debe seleccionar un cliente.'
	        });

        $('#crear_cliente_id').focus();

        return false;
    }

    if (!$('#crear_empresa_id').val()) {

        Swal.fire({
            icon: 'warning',
            title: 'Empresa requerida',
            text: 'Debe seleccionar una empresa.'
        });

        $('#crear_empresa_id').focus();

        return false;
    }

    if (!$('#crear_tipo_ot_id').val()) {

        Swal.fire({
            icon: 'warning',
            title: 'Tipo OT requerido',
            text: 'Debe seleccionar un Tipo de Operación.'
        });

        $('#crear_tipo_ot_id').focus();

        return false;
    }

    return true;
}

// ======================================================
// BOTON GUARDAR
// ======================================================

$(document).on(
    'click',
    '#ot-btn-guardar-crear',
    function () {

        if (!validarNuevaOT()) {
            return;
        }

       guardarNuevaOT();

    }
);

// ======================================================
// GUARDAR NUEVA OT
// ======================================================   
function guardarNuevaOT() {

    var datos = $('#ot-form-crear').serialize();

    console.log(datos);

    $.ajax({

        url: '/modulos/orden_trabajo/api/ot/ot_crear_api.php',
        type: 'POST',
        dataType: 'json',
        data: datos,

        success: function(resp) {

            console.log(resp);

            if (resp.ok) {

                Swal.fire({
                    icon: 'success',
                    title: 'OT registrada',
                    text: 'La Orden de Trabajo fue creada correctamente.'
                });

                $('#modal-nueva-ot').modal('hide');

                if (
                    typeof tablaOT !== 'undefined'
                ) {

                    tablaOT.ajax.reload(
                        null,
                        false
                    );

                }

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text:
                        resp.mensaje ||
                        'No fue posible registrar la OT.'
                });

            }

        },

        error: function(xhr) 
			{
            console.log('STATUS:', xhr.status);

			console.log('RESPONSE TEXT:');
			console.log(xhr.responseText);

			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Error de comunicación con el servidor.'
            	});
			}

    });

}

