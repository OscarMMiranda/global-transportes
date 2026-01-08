 // Manejo de modales para Conductores
// ----------------------------------------------
// archivo: modulos/conductores/assets/modal.js

$(function () {
  const modalConductor    = document.getElementById('modalConductor');
  const modalVerConductor = document.getElementById('modalVerConductor');

  // 🧼 Resetear formulario al cerrar modal de edición
  modalConductor.addEventListener('hidden.bs.modal', function () {
    $('#formConductor')[0].reset();
    $('#c_id').val('');
    $('#preview_foto').hide();
    $('#c_foto').val('');
    $('#c_activo').prop('checked', true);

    // devolver foco a un elemento visible fuera del modal
    $('#btnNuevoConductor').focus(); // o cualquier botón visible de tu interfaz
  });

  // 🧼 Limpiar contenido al cerrar modal de vista
  modalVerConductor.addEventListener('hidden.bs.modal', function () {
    $('#ver_nombre, #ver_dni, #ver_licencia, #ver_telefono, #ver_correo, #ver_direccion').text('—');
    $('#ver_estado').removeClass('bg-success bg-secondary').text('—');
    $('#ver_foto').attr('src', '').hide();
    $('#sin_foto').show();

    // devolver foco a un elemento visible fuera del modal
    $('#tblActivos').focus(); // por ejemplo la tabla
  });

  console.log('✅ modal.js inicializado correctamente');
});