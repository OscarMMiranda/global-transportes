// JavaScript para la gestión de conductores
// Ubicación: /modulos/conductores/assets/conductores.js

	$(function () {
  		console.log('✅ conductores.js activo');

  		const api = 'api.php';
  		const modalConductor = document.getElementById('modalConductor');
  		const modalVerConductor = document.getElementById('modalVerConductor');


  		// 🔍 Ver conductor (modal solo lectura)
  		$(document).on('click', '.btn-view', function () {
    		const id = $(this).data('id');
    		if (!id) return;

    		console.log('👁️ Ver conductor ID:', id);

    		$.getJSON(api, { op: 'get', id: id })
      		.done(function (res) {
        	console.log('📥 Datos recibidos (ver):', res);
        	if (!res.success || !res.data) {
          		alert('Conductor no encontrado.');
          		return;
        		}

        const data = res.data;
        $('#ver_nombre').text((data.apellidos || '') + ', ' + (data.nombres || ''));
        $('#ver_dni').text(data.dni || '—');
        $('#ver_licencia').text(data.licencia_conducir || '—');
        $('#ver_telefono').text(data.telefono || '—');
        $('#ver_correo').text(data.correo || '—');
        $('#ver_direccion').text(data.direccion || '—');
        $('#ver_estado')
          .removeClass('bg-success bg-secondary')
          .addClass(data.activo == 1 ? 'bg-success' : 'bg-secondary')
          .text(data.activo == 1 ? 'Activo' : 'Inactivo');

        if (data.foto) {
          $('#ver_foto').attr('src', '/uploads/conductores/' + data.foto).show();
          $('#sin_foto').hide();
        } else {
          $('#ver_foto').hide();
          $('#sin_foto').show();
        }

        if (!modalVerConductor) {
          console.error('❌ Modal "modalVerConductor" no encontrado en el DOM');
          return;
        }
        bootstrap.Modal.getOrCreateInstance(modalVerConductor).show();
      })
      .fail(function () {
        alert('❌ Error al obtener datos del servidor.');
      });
  });

  // ✏️ Editar conductor
  $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    if (!id) return;

    console.log('✏️ Editar conductor ID:', id);

    $.getJSON(api, { op: 'get', id: id })
      .done(function (res) {
        console.log('📥 Datos recibidos (editar):', res.data);
        console.table(res.data);

        if (!res.success || !res.data) {
          alert('Error al obtener datos: ' + res.error);
          return;
        }

        const data = res.data;
        $('#c_id').val(data.id || '');
        $('#c_nombres').val(data.nombres || '');
        $('#c_apellidos').val(data.apellidos || '');
        $('#c_dni').val(data.dni || '');
        $('#c_licencia_conducir').val(data.licencia_conducir || '');
        $('#c_telefono').val(data.telefono || '');
        $('#c_correo').val(data.correo || '');
        $('#c_direccion').val(data.direccion || '');
        $('#c_activo').prop('checked', !!data.activo);

        if (data.foto) {
          $('#preview_foto').attr('src', '/uploads/conductores/' + data.foto).show();
        } else {
          $('#preview_foto').hide();
        }

        $('#c_foto').val('');
        $('#frmConductor input, #frmConductor select').prop('disabled', false);
        $('#frmConductor button[type="submit"]').show();
        $('#btnCancelar').text('Cancelar');

        if (!modalConductor) {
          console.error('❌ Modal "modalConductor" no encontrado en el DOM');
          return;
        }
        bootstrap.Modal.getOrCreateInstance(modalConductor).show();
      })
      .fail(function () {
        alert('❌ Error al obtener datos del servidor.');
      });
  });

  // 🆕 Nuevo conductor
  $('#btnNuevo').click(function () {
    $('#frmConductor')[0].reset();
    $('#c_id').val('');
    $('#c_activo').prop('checked', true);
    $('#preview_foto').hide();
    $('#frmConductor input, #frmConductor select').prop('disabled', false);
    $('#frmConductor button[type="submit"]').show();
    $('#btnCancelar').text('Cancelar');

    if (!modalConductor) {
      console.error('❌ Modal "modalConductor" no encontrado en el DOM');
      return;
    }
    bootstrap.Modal.getOrCreateInstance(modalConductor).show();
  });

  	// 🧼 Reset modal solo si es nuevo
  	if (modalConductor) {
    	modalConductor.addEventListener('show.bs.modal', function () {
			const id = $('#c_id').val();
      		if (!id) {
        		$('#frmConductor')[0].reset();
        		$('#preview_foto').hide();
      			}
    		});
  		}

  	// 🔒 Evitar advertencia de aria-hidden con foco retenido
  	$('#modalConductor, #modalVerConductor').on('hidden.bs.modal', function () {
		setTimeout(() => {
			$('#btnNuevo').trigger('focus');
    			}, 100);
  		});
});

$('#modalConductor, #modalVerConductor').on('hidden.bs.modal', function () {
  setTimeout(() => {
    $('#btnNuevo').trigger('focus'); // o cualquier botón visible fuera del modal
  }, 100);
});