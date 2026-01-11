// Manejo de modales para Conductores
// ----------------------------------------------
// archivo: modulos/conductores/assets/modal.js

$(function () {

  const modalConductor    = document.getElementById('modalConductor');
  const modalVerConductor = document.getElementById('modalVerConductor');

  // ============================================================
  //  RESET AL CERRAR MODAL DE CREAR / EDITAR
  // ============================================================
  modalConductor.addEventListener('hidden.bs.modal', function () {

    // Reset completo del formulario
    const form = $('#formConductor')[0];
    if (form) form.reset();

    // Reset campos ocultos
    $('#c_id').val('');

    // Reset foto
    $('#preview_foto').hide().attr('src', '');
    $('#c_foto').val('');

    // Estado por defecto
    $('#c_activo').prop('checked', true);

    // Devolver foco al botón principal
    $('#btnNuevoConductor').focus();
  });


  // ============================================================
  //  RESET AL CERRAR MODAL DE VER DETALLES
  // ============================================================
  modalVerConductor.addEventListener('hidden.bs.modal', function () {

    $('#ver_nombre').text('—');
    $('#ver_apellidos').text('—');
    $('#ver_dni').text('—');
    $('#ver_licencia').text('—');
    $('#ver_telefono').text('—');
    $('#ver_correo').text('—');
    $('#ver_direccion').text('—');

    $('#ver_estado')
      .removeClass('bg-success bg-secondary')
      .text('—');

    $('#ver_foto').attr('src', '').hide();
    $('#sin_foto').show();

    // Foco de accesibilidad
    $('#tablaConductores').focus();
  });


  // ============================================================
  //  PREVIEW DE FOTO (cuando se selecciona un archivo)
  // ============================================================
  $(document).on('change', '#c_foto', function () {
    const file = this.files[0];
    if (!file) {
      $('#preview_foto').hide();
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      $('#preview_foto').attr('src', e.target.result).show();
    };
    reader.readAsDataURL(file);
  });


  console.log('✅ modal.js inicializado correctamente');
});