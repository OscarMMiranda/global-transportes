// archivo: /modulos/empleados/assets/form.js

window.Empleados = window.Empleados || {};

(function () {

    $('#formEmpleado').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: '/modulos/empleados/acciones/guardar.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',

            success: function (resp) {

                if (!resp.success) {
                    Swal.fire('Error', resp.error || 'No se pudo guardar', 'error');
                    return;
                }

                Swal.fire('Éxito', 'Empleado guardado correctamente', 'success');

                $('#modalEmpleado').modal('hide');
                Empleados.tabla.ajax.reload(null, false);
            },

            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Error inesperado al guardar', 'error');
            }
        });
    });

})();
