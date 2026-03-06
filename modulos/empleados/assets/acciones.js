console.log("acciones.js de empleados cargado correctamente");

$(document).ready(function () {

    // ===============================
    // GUARDAR EMPLEADO
    // ===============================
    $('#btnGuardarEmpleado').on('click', function () {

        // VALIDACIÓN
        if (!$('#e_nombres').val().trim()) {
            Swal.fire('Validación', 'El campo Nombres es obligatorio', 'warning');
            return;
        }
        if (!$('#e_apellidos').val().trim()) {
            Swal.fire('Validación', 'El campo Apellidos es obligatorio', 'warning');
            return;
        }
        if (!$('#e_dni').val().trim()) {
            Swal.fire('Validación', 'El campo DNI es obligatorio', 'warning');
            return;
        }
        if (!$('#empresa_id').val()) {
            Swal.fire('Validación', 'Debe seleccionar una empresa', 'warning');
            return;
        }
        if (!$('#departamento_id').val()) {
            Swal.fire('Validación', 'Debe seleccionar un departamento', 'warning');
            return;
        }
        if (!$('#provincia_id').val()) {
            Swal.fire('Validación', 'Debe seleccionar una provincia', 'warning');
            return;
        }
        if (!$('#distrito_id').val()) {
            Swal.fire('Validación', 'Debe seleccionar un distrito', 'warning');
            return;
        }

        // ENVÍO
        let form = document.getElementById('formEmpleado');
        let formData = new FormData(form);

        $.ajax({
            url: '/modulos/empleados/acciones/guardar.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',

            beforeSend: function () {
                $('#btnGuardarEmpleado').prop('disabled', true).text('Guardando...');
            },

            success: function (resp) {

                if (!resp.success) {
                    Swal.fire('Error', resp.message || 'No se pudo guardar', 'error');
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Guardado correctamente',
                    timer: 1500,
                    showConfirmButton: false
                });

                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEmpleado'));
                modal.hide();

                if (window.tablaEmpleados) {
                    window.tablaEmpleados.ajax.reload(null, false);
                }
            },

            error: function (xhr) {
                console.error("ERROR AJAX:", xhr.responseText);
                Swal.fire('Error', 'Error inesperado al guardar', 'error');
            },

            complete: function () {
                $('#btnGuardarEmpleado').prop('disabled', false).text('Guardar');
            }
        });

    });

    // ===============================
    // EVENTO: HISTORIAL (CORRECTO)
    // ===============================
    $('#tblActivos, #tblInactivos').on('click', '.btnHistorial', function () {
        const id = $(this).data('id');
        Empleados.abrirHistorial(id);
    });

});


// UNIVERSAL: abrir historial de cualquier módulo
function abrirHistorial(tabla, id) {

    $("#historialContenido").html(`
        <div class="text-center text-muted py-4">
            Cargando historial...
        </div>
    `);

    $("#modalHistorial").modal("show");

    $.ajax({
        url: `/modulos/${tabla}/acciones/historial.php`,
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function(resp) {
            if (resp.success) {
                $("#historialContenido").html(resp.html);
            } else {
                $("#historialContenido").html(`
                    <div class="alert alert-danger">
                        Error al cargar historial.
                    </div>
                `);
            }
        },
        error: function() {
            $("#historialContenido").html(`
                <div class="alert alert-danger">
                    Error de comunicación con el servidor.
                </div>
            `);
        }
    });
}
