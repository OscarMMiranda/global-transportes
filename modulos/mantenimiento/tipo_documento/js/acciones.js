// archivo: /modulos/mantenimiento/tipo_documento/js/acciones.js

// === Desactivar tipo de documento ===
$(document).on('click', '.btn-desactivar', function () {
  const id = $(this).data('id');
  if (!confirm('¿Deseas desactivar este tipo de documento?')) return;

  $.post('ajax/desactivar.php', { id }, function (msg) {
    alert(msg);
    // Recarga limpia sin parámetros para evitar apertura de modal
    window.location.href = window.location.pathname;
  }).fail(function () {
    alert('❌ Error al desactivar.');
  });
});

// === Activar tipo de documento ===
$(document).on('click', '.btn-activar', function () {
  const id = $(this).data('id');
  if (!confirm('¿Deseas reactivar este tipo de documento?')) return;

  $.post('ajax/activar.php', { id }, function (msg) {
    alert(msg);
    // Recarga limpia sin parámetros para evitar apertura de modal
    window.location.href = window.location.pathname;
  }).fail(function () {
    alert('❌ Error al activar.');
  });
});

function inicializarModalNuevo() {
  const $form = $('#modalTipoDocumento form');

  // Resetear el formulario
  $form[0].reset();

  // Limpiar manualmente todos los campos
  $form.find('input[type="text"], input[type="number"], textarea').val('');
  $form.find('select').val('');
  $('#color_etiqueta').val('#ffffff');
  $('#id').val(0);

  // Desmarcar checkboxes
  $form.find('input[type="checkbox"]').prop('checked', false);

  // Activar todos los campos
  $form.find('input, select, textarea').prop('disabled', false);

  // Ocultar imprimir, mostrar guardar
  $('#btn-imprimir').hide();
  $('#btn-guardar').show();

  // Limpiar errores visuales
  $form.find('.is-invalid').removeClass('is-invalid');
  $form.find('.invalid-feedback').remove();

  // Actualizar título
  $('#modalTipoDocumentoLabel').text('Nuevo Tipo de Documento');
}

// === Evento: abrir modal en modo NUEVO ===
$('#btn-nuevo').click(() => {
  const modalEl = document.getElementById('modalTipoDocumento');
  const modal = bootstrap.Modal.getInstance(modalEl);
  if (modal) modal.hide(); // cerrar si está abierto

  inicializarModalNuevo();
  new bootstrap.Modal(modalEl).show();
});