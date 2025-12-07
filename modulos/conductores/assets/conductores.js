// archivo: /modulos/conductores/assets/conductores.js
// Inicialización profesional de DataTables con AJAX para conductores

$(function () {
  console.log('✅ conductores.js cargado');

  // Endpoints
  const listarActivosApi   = '/modulos/conductores/acciones/listar.php?estado=activo';
  const listarInactivosApi = '/modulos/conductores/acciones/listar.php?estado=inactivo';

  // IDs de tablas
  const tblActivos   = '#tblActivos';
  const tblInactivos = '#tblInactivos';

  // Inicializa una DataTable vía AJAX
  function initTable(selector, ajaxUrl) {
    if (!$(selector).length) return;

    // Si ya existe DataTable, destruir
    if ($.fn.DataTable.isDataTable(selector)) {
      $(selector).DataTable().destroy();
    }

    // Limpiar tabla y siempre insertar el thead
    $(selector).empty();
    const thead = `
      <thead class="table-light">
        <tr>
          <th style="width:5%">#</th>
          <th style="width:25%">Apellidos y Nombres</th>
          <th style="width:10%">DNI</th>
          <th style="width:15%">Licencia</th>
          <th style="width:15%">Teléfono</th>
          <th style="width:10%">Estado</th>
          <th style="width:20%" class="text-center">Acciones</th>
        </tr>
      </thead>`;
    $(selector).append(thead);

    // Inicializar DataTable
    $(selector).DataTable({
      ajax: {
        url: ajaxUrl,
        dataSrc: function (json) {
          if (!json || !json.data) {
            console.error('❌ Error en respuesta Ajax:', json);
            return [];
          }
          return json.data;
        }
      },
      columns: [
        { data: null, render: (data, type, row, meta) => meta.row + 1 },
        { data: null, render: r => `${r.apellidos}, ${r.nombres}` },
        { data: 'dni' },
        { data: 'licencia_conducir' },
        { data: 'telefono', defaultContent: '—' },
        { data: 'activo', render: d => d == 1
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-secondary">Inactivo</span>' },
        { data: null, orderable: false, searchable: false, render: r => accionesHTML(r) }
      ],
      language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50],
      responsive: true
    });
  }

  // Botones de acción
  function accionesHTML(r) {
    const verBtn = `<button class="btn btn-sm btn-info btn-view" data-id="${r.id}" title="Ver"><i class="fa fa-eye"></i></button>`;
    if (r.activo == 1) {
      return `
        ${verBtn}
        <button class="btn btn-sm btn-primary btn-edit" data-id="${r.id}" title="Editar"><i class="fa fa-edit"></i></button>
        <button class="btn btn-sm btn-warning btn-soft-delete" data-id="${r.id}" title="Desactivar"><i class="fa fa-trash-can"></i></button>
      `;
    } else {
      return `
        ${verBtn}
        <button class="btn btn-sm btn-success btn-restore" data-id="${r.id}" title="Restaurar"><i class="fa fa-rotate-left"></i></button>
        <button class="btn btn-sm btn-danger btn-delete" data-id="${r.id}" title="Eliminar definitivo"><i class="fa fa-trash"></i></button>
      `;
    }
  }

  // Inicializar ambas tablas
  initTable(tblActivos, listarActivosApi);
  initTable(tblInactivos, listarInactivosApi);

  // Ajuste de columnas al cambiar de tab
  document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(btn => {
    btn.addEventListener('shown.bs.tab', function (e) {
      const target = e.target.getAttribute('data-bs-target');
      if (target === '#panel-activos' && $.fn.DataTable.isDataTable(tblActivos)) {
        $(tblActivos).DataTable().columns.adjust();
      }
      if (target === '#panel-inactivos' && $.fn.DataTable.isDataTable(tblInactivos)) {
        $(tblInactivos).DataTable().columns.adjust();
      }
    });
  });

  // Exponer funciones globales para refrescar tablas
  window.ConductoresDT = {
    reloadActivos:   () => $.fn.DataTable.isDataTable(tblActivos)   && $(tblActivos).DataTable().ajax.reload(),
    reloadInactivos: () => $.fn.DataTable.isDataTable(tblInactivos) && $(tblInactivos).DataTable().ajax.reload()
  };

  // Listener: Ver
  $(document).on('click', '.btn-view', function () {
    const id = $(this).data('id');

    $.ajax({
      url: '/modulos/conductores/acciones/ver.php',
      type: 'GET',
      data: { id: id },
      dataType: 'json',
      success: function (resp) {
        console.log('Respuesta ver.php en Ver:', resp);
        if (resp.success) {
          const c = resp.data;
          $('#ver_nombre').text(`${c.apellidos}, ${c.nombres}`);
          $('#ver_dni').text(c.dni);
          $('#ver_licencia').text(c.licencia_conducir);
          $('#ver_telefono').text(c.telefono || '—');
          $('#ver_correo').text(c.correo || '—');
          $('#ver_direccion').text(c.direccion || '—');
          $('#ver_estado').html(c.activo == 1
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-secondary">Inactivo</span>'
          );

          if (c.foto) {
            $('#ver_foto').attr('src', c.foto).css('display', 'block');
            $('#sin_foto').hide();
          } else {
            $('#ver_foto').hide();
            $('#sin_foto').show();
          }

          $('#modalVerConductor').modal('show');
        } else {
          console.error('❌ Error al cargar conductor:', resp.error);
        }
      },
      error: function (xhr) {
        console.error('❌ Error Ajax:', xhr.responseText);
      }
    });
  });

  // Listener: Editar
  $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    console.log('✏️ Editar conductor', id);

    console.log('👉 Click en botón Editar detectado');

    $.ajax({
      url: '/modulos/conductores/acciones/ver.php',
      type: 'GET',
      data: { id: id },
      dataType: 'json',
      success: function (resp) {
        console.log('Respuesta ver.php en Editar:', resp);
        if (resp.success) {
          const c = resp.data;

          // Llenar formulario
          $('#c_id').val(c.id);
          $('#c_nombres').val(c.nombres);
          $('#c_apellidos').val(c.apellidos);
          $('#c_dni').val(c.dni);
          $('#c_licencia_conducir').val(c.licencia_conducir);
          $('#c_telefono').val(c.telefono);
          $('#c_correo').val(c.correo);
          $('#c_direccion').val(c.direccion);
          $('#c_activo').prop('checked', c.activo == 1);

          if (c.foto) {
            $('#preview_foto').attr('src', c.foto).show();
          } else {
            $('#preview_foto').hide();
          }

          // Abrir modal
          $('#frmConductor').attr('data-mode', 'editar');
          console.log('✅ Datos cargados, abriendo modal', c);
          $('#modalConductor').modal('show');
        } else {
          console.error('❌ Error al cargar conductor:', resp.error);
        }
      },
      error: function (xhr) {
        console.error('❌ Error Ajax:', xhr.responseText);
      }
    });
  });

  // Listener: Guardar (crear/editar) — Bootstrap 4 (jQuery)
  $('#frmConductor').on('submit', function (e) {
    e.preventDefault();
    const mode = $(this).attr('data-mode');
    const formData = new FormData(this);

    const url = mode === 'editar'
      ? '/modulos/conductores/acciones/editar.php'
      : '/modulos/conductores/acciones/crear.php';

    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (resp) {
        console.log('Respuesta guardar:', resp);
        if (resp.success) {
          // Cerrar modal (Bootstrap 4)
          $('#modalConductor').modal('hide');

          // Refrescar tablas
          ConductoresDT.reloadActivos();
          ConductoresDT.reloadInactivos();

          // Limpiar errores
          $('#formError').addClass('d-none').text('');
        } else {
          $('#formError').removeClass('d-none').text(resp.error || 'Error al guardar');
        }
      },
      error: function (xhr) {
        console.error('❌ Error Ajax:', xhr.responseText);
        $('#formError').removeClass('d-none').text('Error de comunicación con el servidor');
      }
    });
  });

  // Placeholder: Desactivar
  $(document).on('click', '.btn-soft-delete', function () {
    const id = $(this).data('id');
    console.log('🗑 Desactivar conductor', id);

    $.ajax({
      url: '/modulos/conductores/acciones/desactivar.php',
      type: 'POST',
      data: { id },
      dataType: 'json',
      success: function (resp) {
        if (resp.success) {
          ConductoresDT.reloadActivos();
        } else {
          console.error('❌ Error al desactivar:', resp.error);
        }
      },
      error: function (xhr) {
        console.error('❌ Error Ajax:', xhr.responseText);
      }
    });
  });

  // Placeholder: Restaurar
  $(document).on('click', '.btn-restore', function () {
    const id = $(this).data('id');
    console.log('♻️ Restaurar conductor', id);

    $.ajax({
      url: '/modulos/conductores/acciones/restaurar.php',
      type: 'POST',
      data: { id },
      dataType: 'json',
      success: function (resp) {
        if (resp.success) {
          ConductoresDT.reloadInactivos();
        } else {
          console.error('❌ Error al restaurar:', resp.error);
        }
      },
      error: function (xhr) {
        console.error('❌ Error Ajax:', xhr.responseText);
      }
    });
  });

  // Placeholder: Eliminar definitivo
  $(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    console.log('❌ Eliminar definitivo', id);

    $.ajax({
      url: '/modulos/conductores/acciones/eliminar.php',
      type: 'POST',
      data: { id },
      dataType: 'json',
      success: function (resp) {
        if (resp.success) {
          ConductoresDT.reloadInactivos();
        } else {
          console.error('❌ Error al eliminar:', resp.error);
        }
      },
      error: function (xhr) {
        console.error('❌ Error Ajax:', xhr.responseText);
      }
    });
  });
});