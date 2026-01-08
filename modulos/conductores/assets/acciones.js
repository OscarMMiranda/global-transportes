// Acciones para Conductores (versión mejorada)
// ----------------------------------------------
// archivo: modulos/conductores/assets/acciones.js

console.log('✅ acciones.js cargado');

// =======================
// Ver conductor
// =======================
$(document).on('click', '.btn-view', function () {
  const id = $(this).data('id');
  if (!id) return Swal.fire('❌ Error', 'ID inválido.', 'error');

  $.get('/modulos/conductores/acciones/ver.php', { id }, function (resp) {
    if (resp && resp.success && resp.data) {
      const c = resp.data;
      $('#ver_nombre').text(`${c.apellidos}, ${c.nombres}`);
      $('#ver_dni').text(c.dni || '—');
      $('#ver_licencia').text(c.licencia_conducir || '—');
      $('#ver_telefono').text(c.telefono || '—');
      $('#ver_correo').text(c.correo || '—');
      $('#ver_direccion').text(c.direccion || '—');
      $('#ver_estado')
        .removeClass('bg-success bg-secondary')
        .addClass(c.activo == 1 ? 'bg-success' : 'bg-secondary')
        .text(c.activo == 1 ? 'Activo' : 'Inactivo');

      if (c.foto) {
        $('#ver_foto').attr('src', c.foto).show();
        $('#sin_foto').hide();
      } else {
        $('#ver_foto').hide();
        $('#sin_foto').show();
      }

      bootstrap.Modal.getOrCreateInstance(document.getElementById('modalVerConductor')).show();
    } else {
      Swal.fire('❌ Error', resp.error || 'No se pudo cargar el conductor.', 'error');
    }
  }, 'json').fail(() => Swal.fire('❌ Error', 'No se pudo conectar al servidor.', 'error'));
});

// =======================
// Editar conductor
// =======================
$(document).on('click', '.btn-edit', function () {
  const id = $(this).data('id');
  if (!id) return Swal.fire('❌ Error', 'ID inválido.', 'error');

  $.get('/modulos/conductores/acciones/ver.php', { id }, function (resp) {
    if (resp && resp.success && resp.data) {
      const c = resp.data;
      $('#c_id').val(c.id || 0);
      $('#c_nombres').val(c.nombres);
      $('#c_apellidos').val(c.apellidos);
      $('#c_dni').val(c.dni);
      $('#c_licencia').val(c.licencia_conducir);
      $('#c_telefono').val(c.telefono);
      $('#c_correo').val(c.correo);
      $('#c_direccion').val(c.direccion);
      $('#c_activo').prop('checked', c.activo == 1);

      if (c.foto) {
        $('#preview_foto').attr('src', c.foto).show();
      } else {
        $('#preview_foto').hide();
      }

      bootstrap.Modal.getOrCreateInstance(document.getElementById('modalConductor')).show();
    } else {
      Swal.fire('❌ Error', resp.error || 'No se pudo cargar el conductor.', 'error');
    }
  }, 'json').fail(() => Swal.fire('❌ Error', 'No se pudo conectar al servidor.', 'error'));
});

// =======================
// Soft delete (desactivar)
// =======================
$(document).on('click', '.btn-soft-delete', function () {
  const id = $(this).data('id');
  if (!id) return Swal.fire('❌ Error', 'ID inválido.', 'error');

  $.post('/modulos/conductores/acciones/desactivar.php', { id }, function (resp) {
    if (resp && resp.success) {
      ConductoresDT.reloadActivos?.();
      ConductoresDT.reloadInactivos?.();
      Swal.fire('✅ Correcto', 'Conductor desactivado.', 'success');
    } else {
      Swal.fire('❌ Error', resp.error || 'No se pudo desactivar.', 'error');
    }
  }, 'json').fail(() => Swal.fire('❌ Error', 'No se pudo conectar al servidor.', 'error'));
});

// =======================
// Restaurar
// =======================
$(document).on('click', '.btn-restore', function () {
  const id = $(this).data('id');
  if (!id) return Swal.fire('❌ Error', 'ID inválido.', 'error');

  $.post('/modulos/conductores/acciones/restaurar.php', { id }, function (resp) {
    if (resp && resp.success) {
      ConductoresDT.reloadInactivos?.();
      ConductoresDT.reloadActivos?.();
      Swal.fire('✅ Correcto', 'Conductor restaurado.', 'success');
    } else {
      Swal.fire('❌ Error', resp.error || 'No se pudo restaurar.', 'error');
    }
  }, 'json').fail(() => Swal.fire('❌ Error', 'No se pudo conectar al servidor.', 'error'));
});


// =======================
// Eliminar definitivo (con confirmación)
// =======================
	$(document).on('click', '.btn-delete', function () {
  		const id = $(this).data('id');
  		if (!id) {
    		Swal.fire('❌ Error', 'ID inválido.', 'error');
    		return;
  			}

  		Swal.fire({
    		title: '¿Eliminar definitivamente?',
    		text: 'Esta acción no se puede deshacer. El conductor será borrado de forma permanente.',
    		icon: 'warning',
    		showCancelButton: true,
    		confirmButtonColor: '#d33',
    		cancelButtonColor: '#3085d6',
    		confirmButtonText: 'Sí, eliminar',
    		cancelButtonText: 'Cancelar'
  			})
		.then((result) => {
    		if (result.isConfirmed) {
      			$.post('/modulos/conductores/acciones/eliminar.php', { id }, function (resp) {
        			if (resp && resp.success) {
          				ConductoresDT.reloadInactivos?.();
          				Swal.fire('✅ Eliminado', 'El conductor fue borrado permanentemente.', 'success');
        				} 
					else {
          				Swal.fire('❌ Error', resp.error || 'No se pudo eliminar.', 'error');
        				}
      				}, 'json').fail(() => {
        		Swal.fire('❌ Error', 'No se pudo conectar al servidor.', 'error');
      		});
	    }
  	});
});



// =======================
// Guardar conductor (alta o edición)
// =======================
$('#formConductor').on('submit', function (e) {
  e.preventDefault();
  const formData = new FormData(this);

  $.ajax({
    type: 'POST',
    url: '/modulos/conductores/acciones/guardar.php',
    data: formData,
    contentType: false,
    processData: false,
    dataType: 'json',
    success: function (resp) {
      if (resp && resp.success) {
        Swal.fire('✅ Guardado', 'El conductor fue registrado correctamente.', 'success');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalConductor')).hide();

        setTimeout(() => {
          ConductoresDT.reloadActivos?.();
          ConductoresDT.reloadInactivos?.();
        }, 300);
      } else {
        Swal.fire('❌ Error', resp.error || 'No se pudo guardar el conductor.', 'error');
      }
    },
    error: function (xhr, status, err) {
      if (status === 'abort') {
        console.warn('⚠️ Petición abortada en guardar (reload en curso)');
        return;
      }
      Swal.fire('❌ Error', 'No se pudo enviar datos al servidor.', 'error');
    }
  });
});