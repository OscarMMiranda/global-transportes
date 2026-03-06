// archivo: modulos/empleados/assets/modal_eliminar.js

console.log("modal_eliminar.js cargado correctamente");

// ============================================================
// ABRIR MODAL ELIMINAR
// ============================================================
$(document).on('click', '.btnEliminar', function () {

    const id = $(this).data('id');

    $.ajax({
        url: '/modulos/empleados/acciones/obtener.php',
        type: 'GET',
        data: { id },
        dataType: 'json',
        success: function (resp) {

            if (!resp.success) {
                Swal.fire("Error", resp.message, "error");
                return;
            }

            const e = resp.data;

            $('#elim_id').text(e.id);
            $('#elim_nombres').text(e.nombres + ' ' + e.apellidos);
            $('#elim_dni').text(e.dni);
            $('#elim_id_hidden').val(e.id);

            const modal = new bootstrap.Modal(document.getElementById('modalEliminarEmpleado'));
            modal.show();
        }
    });
});

// ============================================================
// CONFIRMAR ELIMINACIÓN
// ============================================================
$('#btnConfirmarEliminar').on('click', function () {

    const id = $('#elim_id_hidden').val();

    $.ajax({
        url: '/modulos/empleados/acciones/desactivar.php',
        type: 'POST',
        data: { id },
        dataType: 'json',

        success: function (resp) {

            if (!resp.success) {
                Swal.fire("Error", resp.message, "error");
                return;
            }

            Swal.fire("Eliminado", "El empleado fue eliminado correctamente", "success");

            const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminarEmpleado'));
            modal.hide();

            if (window.tablaEmpleados) {
                window.tablaEmpleados.ajax.reload(null, false);
            }
        }
    });
});
