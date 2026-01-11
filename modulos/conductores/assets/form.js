// Manejo de formularios para Conductores
// ----------------------------------------------
// archivo: modulos/conductores/assets/form.js

$(function () {

  const modalConductor = document.getElementById('modalConductor');

  // ============================================================
  //  VALIDACIÓN BÁSICA
  // ============================================================
  function validarFormulario() {

    const nombres   = $('#c_nombres').val().trim();
    const apellidos = $('#c_apellidos').val().trim();
    const dni       = $('#c_dni').val().trim();
    const licencia  = $('#c_licencia').val().trim();
    const correo    = $('#c_correo').val().trim();

    if (nombres === '' || apellidos === '') {
      Swal.fire('Campos obligatorios', 'Debe ingresar nombres y apellidos.', 'warning');
      return false;
    }

    if (dni !== '' && !/^\d{8}$/.test(dni)) {
      Swal.fire('DNI inválido', 'El DNI debe tener 8 dígitos numéricos.', 'warning');
      return false;
    }

    if (licencia === '') {
      Swal.fire('Licencia requerida', 'Debe ingresar número de licencia.', 'warning');
      return false;
    }

    if (correo !== '' && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(correo)) {
      Swal.fire('Correo inválido', 'Ingrese un correo electrónico válido.', 'warning');
      return false;
    }

    return true;
  }


  // ============================================================
  //  SUBMIT DEL FORMULARIO (CREAR / EDITAR)
  // ============================================================
  $('#formConductor').on('submit', function (e) {
    e.preventDefault();

    if (!validarFormulario()) return;

    const modo = $('#modalConductor').attr('data-modo'); // crear | editar
    const formData = new FormData(this);

    const url =
      modo === 'crear'
        ? '/modulos/conductores/acciones/guardar.php'
        : '/modulos/conductores/acciones/editar.php';

    $.ajax({
      url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',

      success: function (res) {

        if (!res.ok) {
          Swal.fire('Error', res.mensaje || 'No se pudo guardar el conductor.', 'error');
          return;
        }

        Swal.fire('OK', res.mensaje, 'success');

        // Recargar DataTable
        if (typeof tablaConductores !== 'undefined') {
          tablaConductores.ajax.reload(null, false);
        }

        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(modalConductor);
        modal.hide();
      },

      error: function (xhr) {
        Swal.fire('Error inesperado', 'HTTP ' + xhr.status, 'error');
      }
    });
  });


  // ============================================================
  //  PREVIEW DE FOTO
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


  console.log('✅ form.js inicializado correctamente');
});