// archivo: /modulos/conductores/assets/form.js
// FORMULARIO DEL MÓDULO

window.Conductores = window.Conductores || {};

(function () {

    $('#formConductor').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/modulos/conductores/acciones/guardar.php', // ÚNICO ENDPOINT
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
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