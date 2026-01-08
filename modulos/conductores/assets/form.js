// Manejo de formularios para Conductores
// ----------------------------------------------
// archivo: modulos/conductores/assets/form.js

$(function () {
  const guardarApi = '/modulos/conductores/acciones/guardar.php';
  const modalConductor = document.getElementById('modalConductor');

  // Validación básica de campos
  function validarFormulario() {
    let nombres = $('#c_nombres').val()?.trim() || '';
    let apellidos = $('#c_apellidos').val()?.trim() || '';
    let dni = $('#c_dni').val()?.trim() || '';
    let licencia = $('#c_licencia').val()?.trim() || '';
    let correo = $('#c_correo').val()?.trim() || '';

    console.log('DEBUG validarFormulario:', {nombres, apellidos, dni, licencia, correo});

    if (nombres === '' || apellidos === '') {
      Swal.fire('⚠️ Campos obligatorios', 'Debe ingresar nombres y apellidos.', 'warning');
      return false;
    }
    if (dni !== '' && !/^\d{8}$/.test(dni)) {
      Swal.fire('⚠️ DNI inválido', 'El DNI debe tener 8 dígitos numéricos.', 'warning');
      return false;
    }
    if (licencia === '') {
      Swal.fire('⚠️ Licencia requerida', 'Debe ingresar número de licencia.', 'warning');
      return false;
    }
    if (correo !== '' && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(correo)) {
      Swal.fire('⚠️ Correo inválido', 'Ingrese un correo electrónico válido.', 'warning');
      return false;
    }
    return true;
  }

  // Envío del formulario
  $('#formConductor').on('submit', function (e) {
    e.preventDefault();
    if (!validarFormulario()) {
      console.warn('❌ Validación fallida, no se envía el formulario');
      return;
    }

    const formData = new FormData(this);

    $.ajax({
      url: guardarApi,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        console.log('DEBUG respuesta servidor:', res);
        if (res.success) {
          Swal.fire('✅ Guardado', 'El conductor fue guardado correctamente.', 'success');

          // Recargar DataTables con pequeña espera para evitar aborts
          if (typeof ConductoresDT !== 'undefined') {
            setTimeout(() => {
              if (ConductoresDT.reloadActivos) {
                ConductoresDT.reloadActivos();
                console.log('🔄 Tabla de Activos recargada');
              }
              if (ConductoresDT.reloadInactivos) {
                ConductoresDT.reloadInactivos();
                console.log('🔄 Tabla de Inactivos recargada');
              }
            }, 300);
          }

          $('#formConductor')[0].reset();
          bootstrap.Modal.getOrCreateInstance(modalConductor).hide();
        } else {
          Swal.fire('❌ Error', res.error || 'No se pudo guardar el conductor.', 'error');
        }
      },
      error: function (xhr, status, err) {
        if (status === 'abort') {
          console.warn('⚠️ Petición abortada al guardar (reload en curso)');
          return;
        }
        Swal.fire('❌ Error', 'No se pudo enviar datos al servidor.', 'error');
        console.error('AJAX error:', status, err);
      }
    });
  });

  console.log('✅ form.js inicializado correctamente');
});