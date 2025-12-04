// JavaScript para la gestión de conductores
// Ubicación: /modulos/conductores/assets/conductores.js

$(function () {
  console.log('✅ conductores.js activo');

  // Endpoints modulares
  const listarApi    = '/modulos/conductores/acciones/listar.php';
  const verApi       = '/modulos/conductores/acciones/ver.php';
  const guardarApi   = '/modulos/conductores/acciones/guardar.php';
  const editarApi    = '/modulos/conductores/acciones/editar.php';
  const eliminarApi  = '/modulos/conductores/acciones/eliminar.php';
  const borrarApi    = '/modulos/conductores/acciones/borrar.php';
  const restaurarApi = '/modulos/conductores/acciones/restaurar.php';

  // Modales
  const modalConductor    = document.getElementById('modalConductor');
  const modalVerConductor = document.getElementById('modalVerConductor');

  // 🔁 Cargar lista de conductores
  function cargarConductores() {
  $('#contenedorTablaConductores').html('<div class="text-center p-3">🔄 Cargando conductores...</div>');

  $.getJSON(listarApi)
    .done(function (res) {
      if (!res.success || !res.data) {
        $('#contenedorTablaConductores').html('<div class="alert alert-warning">⚠️ No se encontraron conductores.</div>');
        return;
      }

      const conductores = res.data;
      let html = `
        <table id="listaConductores" class="table table-bordered table-hover table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Apellidos y Nombres</th>
              <th>DNI</th>
              <th>Licencia</th>
              <th>Teléfono</th>
              <th>Estado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
      `;

      conductores.forEach((c, i) => {
        html += `
          <tr data-id="${c.id}">
            <td>${i + 1}</td>
            <td>${c.apellidos}, ${c.nombres}</td>
            <td>${c.dni}</td>
            <td>${c.licencia_conducir}</td>
            <td>${c.telefono || '—'}</td>
            <td>
              ${c.activo == 1
                ? '<span class="badge bg-success">Activo</span>'
                : '<span class="badge bg-secondary">Inactivo</span>'}
            </td>
            <td class="text-center">
              <button class="btn btn-sm btn-info btn-view" data-id="${c.id}" title="Ver"><i class="fa fa-eye"></i></button>
              ${c.activo == 1
                ? `
                  <button class="btn btn-sm btn-primary btn-edit" data-id="${c.id}" title="Editar"><i class="fa fa-edit"></i></button>
                  <button class="btn btn-sm btn-warning btn-soft-delete" data-id="${c.id}" title="Desactivar"><i class="fa fa-trash-can"></i></button>
                `
                : `
                  <button class="btn btn-sm btn-success btn-restore" data-id="${c.id}" title="Restaurar"><i class="fa fa-rotate-left"></i></button>
                  <button class="btn btn-sm btn-danger btn-delete" data-id="${c.id}" title="Eliminar definitivo"><i class="fa fa-trash"></i></button>
                `}
            </td>
          </tr>
        `;
      });

      html += `</tbody></table>`;
      $('#contenedorTablaConductores').html(html);

      // 🔑 Reinicializar DataTables
      $('#listaConductores').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        pageLength: 10,
        destroy: true // permite reinicializar sin errores
      });
    })
    .fail(function () {
      $('#contenedorTablaConductores').html('<div class="alert alert-danger">❌ Error al cargar la tabla.</div>');
    });
}


// 🚀 Inicializar DataTable con AJAX
const tablaConductores = $('#listaConductores').DataTable({
  ajax: {
    url: listarApi,
    dataSrc: 'data' // porque listar.php devuelve { success:true, data:[...] }
  },
  columns: [
    { data: null, render: (d,t,r,i) => i+1 }, // índice
    { data: null, render: r => `${r.apellidos}, ${r.nombres}` },
    { data: 'dni' },
    { data: 'licencia_conducir' },
    { data: 'telefono', defaultContent: '—' },
    { data: 'activo', render: d => d == 1 
        ? '<span class="badge bg-success">Activo</span>' 
        : '<span class="badge bg-secondary">Inactivo</span>' },
    { data: null, render: r => accionesHTML(r) } // función que genera botones
  ],
  language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
  pageLength: 10
});



  // 🔍 Ver conductor
  $(document).on('click', '.btn-view', function () {
    const id = $(this).data('id');
    if (!id) return;

    $.getJSON(verApi, { id: id })
      .done(function (res) {
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

    $.getJSON(verApi, { id: id })
      .done(function (res) {
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
    $('#c_foto').val('');
    $('#preview_foto').attr('src', '').hide();
    $('#frmConductor input, #frmConductor select').prop('disabled', false);
    $('#frmConductor button[type="submit"]').show();
    $('#btnCancelar').text('Cancelar');

    bootstrap.Modal.getOrCreateInstance(modalConductor).show();
  });

  // 💾 Guardar / Editar conductor
  $('#frmConductor').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const id = $('#c_id').val();
    const url = id ? editarApi : guardarApi;

    $.ajax({
      	url		: url,
      	method	: 'POST',
      	data		: formData,
      	processData: false,
      	contentType: false,
      	dataType	: 'json'
    })
.done(function (res) {
  if (res.success) {
    Swal.fire('✅ Guardado', 'El conductor se guardó correctamente.', 'success'); // SweetAlert2
    bootstrap.Modal.getOrCreateInstance(modalConductor).hide();

    // 🔑 Destruir DataTable antes de recargar
    if ($.fn.DataTable.isDataTable('#listaConductores')) {
      $('#listaConductores').DataTable().destroy();
    }

    cargarConductores();
  } else {
    Swal.fire('⚠️ Error', res.error || 'Respuesta inválida.', 'error');
  }
})



  // 🔒 Evitar advertencia de aria-hidden con foco retenido
  $('#modalConductor, #modalVerConductor').on('hidden.bs.modal', function () {
    setTimeout(() => {
      document.getElementById('btnNuevo')?.focus();
    }, 100);
  });

  // 🟡 Desactivar conductor (soft delete)
  $(document).on('click', '.btn-soft-delete', function () {
    const id = $(this).data('id');
    if (!id || !confirm('¿Deseas desactivar este conductor?')) return;

    $.post(eliminarApi, { id: id }, function (res) {
      if (res.success) {
        alert('🟡 Conductor desactivado.');
        cargarConductores(); // recarga la tabla
      } else {
        alert('❌ Error al desactivar: ' + (res.error || 'Respuesta inválida.'));
      }
    }, 'json').fail(function () {
      alert('❌ Error al enviar solicitud al servidor.');
    });
  });

  // ✅ Restaurar conductor (activar nuevamente)
  $(document).on('click', '.btn-restore', function () {
    const id = $(this).data('id');
    if (!id || !confirm('¿Deseas restaurar este conductor?')) return;

    $.post(restaurarApi, { id: id }, function (res) {
      if (res.success) {
        alert('✅ Conductor restaurado.');
        cargarConductores(); // recarga la tabla
      } else {
        alert('❌ Error al restaurar: ' + (res.error || 'Respuesta inválida.'));
      }
    }, 'json').fail(function () {
      alert('❌ Error al enviar solicitud al servidor.');
    });
  });

  // ❌ Eliminar conductor definitivamente
  $(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    if (!id || !confirm('⚠️ Esta acción es irreversible. ¿Eliminar definitivamente este conductor?')) return;

    $.post(borrarApi, { id: id }, function (res) {
      if (res.success) {
        alert('🗑️ Conductor eliminado permanentemente.');
        cargarConductores(); // recarga la tabla
      } else {
        alert('❌ Error al eliminar: ' + (res.error || 'Respuesta inválida.'));
      }
    }, 'json').fail(function () {
      alert('❌ Error al enviar solicitud al servidor.');
    });
  });

  // 🚀 Cargar lista al iniciar
  cargarConductores();
});