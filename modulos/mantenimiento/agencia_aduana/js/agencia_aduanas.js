	//	archivo	:	/modulos/mantenimiento/agencia_aduana/js/agencia_aduanas.js

$(document).ready(function () {
  cargarActivos();
  cargarInactivos();

  $('#btnNuevaAgencia').on('click', function () {
    abrirModalAgregar();
  });
});

// 🔄 Cargar agencias activas
function cargarActivos() {
  $('#contenedorActivos').html('<div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i> Cargando agencias activas...</div>');
  $.get('/modulos/mantenimiento/agencia_aduana/ajax/lista_activos.php', function (html) {
    $('#contenedorActivos').html(html);
    inicializarDataTables(); // ✅ activa búsqueda y paginación
  }).fail(function () {
    $('#contenedorActivos').html('<div class="alert alert-danger text-center">❌ Error al cargar agencias activas.</div>');
  });
}

// 🔄 Cargar agencias eliminadas
function cargarInactivos() {
  $('#contenedorInactivos').html('<div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i> Cargando agencias eliminadas...</div>');
  $.get('/modulos/mantenimiento/agencia_aduana/ajax/lista_inactivos.php', function (html) {
    $('#contenedorInactivos').html(html);
    inicializarDataTables(); // ✅ activa búsqueda y paginación
  }).fail(function () {
    $('#contenedorInactivos').html('<div class="alert alert-danger text-center">❌ Error al cargar agencias eliminadas.</div>');
  });
}

// 🆕 Abrir modal para agregar agencia
function abrirModalAgregar() {
  const $contenedor = $('#contenedorFormularioAgregarAgencia');
  $('#modalAgregarAgencia').modal('show');
  $contenedor.html('<div class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Cargando formulario...</div>');

  $.get('/modulos/mantenimiento/agencia_aduana/ajax/form_create.php', function (html) {
    $contenedor.html(html);
  }).fail(function () {
    $contenedor.html('<div class="alert alert-danger text-center">❌ Error al cargar el formulario.</div>');
  });
}

// 🗑 Eliminar agencia
function eliminarAgencia(id) {
  if (!confirm('¿Eliminar esta agencia?')) return;
  if (!id || id <= 0) return alert('❌ ID inválido.');

  $.post('/modulos/mantenimiento/agencia_aduana/ajax/eliminar.php', { id }, function () {
    cargarActivos();
    cargarInactivos();
  }).fail(function () {
    alert('❌ Error al eliminar la agencia.');
  });
}

// ⟳ Reactivar agencia
function reactivarAgencia(id) {
  if (!id || id <= 0) return alert('❌ ID inválido.');

  $.post('/modulos/mantenimiento/agencia_aduana/ajax/reactivar.php', { id }, function () {
    cargarActivos();
    cargarInactivos();
  }).fail(function () {
    alert('❌ Error al reactivar la agencia.');
  });
}

// ✎ Abrir modal de edición
function abrirModalEditar(id) {
  if (!id || id <= 0) return alert('❌ ID inválido.');

  const $contenedor = $('#contenedorFormularioEditar');
  const modal = new bootstrap.Modal(document.getElementById('modalEditarAgencia'));

  $contenedor.html('<div class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Cargando formulario de edición...</div>');

  $.get('/modulos/mantenimiento/agencia_aduana/modales/editar.php', { id }, function (html) {
    $contenedor.html(html);
    modal.show();
  }).fail(function () {
    $contenedor.html('<div class="alert alert-danger text-center">❌ Error al cargar el formulario de edición.</div>');
    modal.show();
  });
}

// 👁 Ver detalles
function verAgencia(id) {
  if (!id || id <= 0) return alert('❌ ID inválido.');

  const $contenedor = $('#contenedorDetalleAgencia');
  const modal = new bootstrap.Modal(document.getElementById('modalVerAgencia'));

  $contenedor.html('<div class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Cargando detalles...</div>');

  $.get('/modulos/mantenimiento/agencia_aduana/ajax/ver.php', { id }, function (html) {
    $contenedor.html(html);
    modal.show();
  }).fail(function () {
    $contenedor.html('<div class="alert alert-danger text-center">❌ Error al cargar detalles.</div>');
    modal.show();
  });
}

// 🌐 Cargar provincias según departamento
$(document).on('change', '#departamento_id', function () {
  const id = $(this).val();
  $('#provincia_id').html('<option value="">Cargando provincias...</option>');
  $('#distrito_id').html('<option value="">Seleccione distrito...</option>');

  $.get('/modulos/mantenimiento/agencia_aduana/ajax/provincias_por_departamento.php', { id }, function (data) {
    const opciones = JSON.parse(data).map(p =>
      `<option value="${p.id}">${p.nombre}</option>`
    );
    $('#provincia_id').html('<option value="">Seleccione provincia...</option>' + opciones.join(''));
  }).fail(function () {
    $('#provincia_id').html('<option value="">❌ Error al cargar provincias</option>');
  });
});

// 🌐 Cargar distritos según provincia
$(document).on('change', '#provincia_id', function () {
  const id = $(this).val();
  $('#distrito_id').html('<option value="">Cargando distritos...</option>');

  $.get('/modulos/mantenimiento/agencia_aduana/ajax/distritos_por_provincia.php', { id }, function (data) {
    const opciones = JSON.parse(data).map(d =>
      `<option value="${d.id}">${d.nombre}</option>`
    );
    $('#distrito_id').html('<option value="">Seleccione distrito...</option>' + opciones.join(''));
  }).fail(function () {
    $('#distrito_id').html('<option value="">❌ Error al cargar distritos</option>');
  });
});

// 💾 Guardar edición
$(document).on('submit', '#formEditarAgencia', function (e) {
  e.preventDefault();

  const datos = $(this).serialize();
  const $btn = $(this).find('button[type="submit"]');
  $btn.prop('disabled', true).text('Guardando...');

  $.post('/modulos/mantenimiento/agencia_aduana/ajax/actualizar.php', datos, function (respuesta) {
    if (respuesta.trim().startsWith('✅')) {
      $('#modalEditarAgencia').modal('hide');
      cargarActivos();
      cargarInactivos();
    } else {
      $('#contenedorFormularioEditar .mensaje-error').remove();
      $('#contenedorFormularioEditar').prepend(
        `<div class="alert alert-danger mensaje-error text-center mb-3">${respuesta}</div>`
      );
    }
  }).fail(function () {
    alert('❌ Error al guardar los cambios.');
  }).always(function () {
    $btn.prop('disabled', false).text('Guardar cambios');
  });
});

// 🆕 Guardar nueva agencia
$(document).on('submit', '#formCrearAgencia', function (e) {
  e.preventDefault();

  const datos = $(this).serialize();
  const $btn = $(this).find('button[type="submit"]');
  $btn.prop('disabled', true).text('Guardando...');

  // 🧠 Trazabilidad visual completa
  const departamentoId = $('#departamento_id').val();
  const provinciaId = $('#provincia_id').val();
  const distritoId = $('#distrito_id').val();

  const departamentoNombre = $('#departamento_id option:selected').text();
  const provinciaNombre = $('#provincia_id option:selected').text();
  const distritoNombre = $('#distrito_id option:selected').text();

  console.log('📤 Enviando datos del formulario:');
  console.log('➡️ departamento_id:', departamentoId, '| nombre:', departamentoNombre);
  console.log('➡️ provincia_id:', provinciaId, '| nombre:', provinciaNombre);
  console.log('➡️ distrito_id:', distritoId, '| nombre:', distritoNombre);

  $.post('/modulos/mantenimiento/agencia_aduana/ajax/guardar.php', datos, function (respuesta) {
    if (respuesta.trim().startsWith('✅')) {
      $('#modalAgregarAgencia').modal('hide');
      cargarActivos();
      cargarInactivos();
    } else {
      $('#contenedorFormularioAgregarAgencia .mensaje-error').remove();
      $('#contenedorFormularioAgregarAgencia').prepend(
        `<div class="alert alert-danger mensaje-error text-center mb-3">${respuesta}</div>`
      );
    }
  }).fail(function () {
    alert('❌ Error al guardar la agencia.');
  }).always(function () {
    $btn.prop('disabled', false).text('Guardar');
  });
});

function inicializarDataTables() {
  if ($.fn.DataTable.isDataTable('#tablaActivos')) {
    $('#tablaActivos').DataTable().destroy();
  }
  $('#tablaActivos').DataTable({
    language: {
      search: "🔍 Buscar:",
      lengthMenu: "Mostrar _MENU_ registros",
      zeroRecords: "No se encontraron coincidencias",
      info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty: "Sin registros disponibles",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "→",
        previous: "←"
      }
    },
    order: [[0, 'asc']],
    pageLength: 10
  });

  if ($.fn.DataTable.isDataTable('#tablaInactivos')) {
    $('#tablaInactivos').DataTable().destroy();
  }
  $('#tablaInactivos').DataTable({
    language: {
      search: "🔍 Buscar:",
      lengthMenu: "Mostrar _MENU_ registros",
      zeroRecords: "No se encontraron coincidencias",
      info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty: "Sin registros disponibles",
      paginate: {
        first: "Primero",
        last: "Último",
        next: "→",
        previous: "←"
      }
    },
    order: [[0, 'asc']],
    pageLength: 10
  });
}