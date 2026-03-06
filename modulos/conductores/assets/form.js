// archivo: /modulos/conductores/assets/form.js
// FORMULARIO DEL MÓDULO

window.Conductores = window.Conductores || {};

(function () {

    $('#formConductor').on('submit', function (e) {
        e.preventDefault();

        // ============================================================
        // VALIDACIÓN PREVIA (FRONTEND)
        // ============================================================
        const nombres      = $('#c_nombres').val().trim();
        const apellidos    = $('#c_apellidos').val().trim();
        const dni          = $('#c_dni').val().trim();
        const licencia     = $('#c_licencia').val().trim();
        const empresa      = $('#empresa_id').val();
        const departamento = $('#departamento_id').val();
        const provincia    = $('#provincia_id').val();
        const distrito     = $('#distrito_id').val();

        if (
            nombres === '' ||
            apellidos === '' ||
            dni.length !== 8 ||
            licencia === '' ||
            empresa === '' ||
            departamento === '' ||
            provincia === '' ||
            distrito === ''
        ) {
            Swal.fire(
                'Campos incompletos',
                'Debe completar todos los campos obligatorios antes de guardar.',
                'warning'
            );
            return;
        }

        // ============================================================
        // ENVÍO REAL DEL FORMULARIO
        // ============================================================
        const formData = new FormData(this);

        $.ajax({
            url: '/modulos/conductores/acciones/guardar.php', // ÚNICO ENDPOINT
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',

            success: function (resp) {

                console.log("RESPUESTA GUARDAR:", resp);

                if (!resp || resp.success !== true) {
                    Swal.fire('Error', resp.error || 'No se pudo guardar', 'error');
                    return;
                }

                Swal.fire('Éxito', 'Datos guardados correctamente', 'success');

                $('#modalConductor').modal('hide');

                Conductores.tablaActivos.ajax.reload(null, false);
                Conductores.tablaInactivos.ajax.reload(null, false);
            },

            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Error inesperado al guardar', 'error');
            }
        });
    });

})();
