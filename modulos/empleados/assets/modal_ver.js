// archivo: modulos/empleados/assets/modal_ver.js

console.log("modal_ver.js cargado correctamente");

// ============================================================
// Abrir modal en modo VER
// ============================================================
Empleados.abrirVer = function (id) {

    $.ajax({
        url: '/modulos/empleados/acciones/obtener.php',
        type: 'GET',
        data: { id },
        dataType: 'json',

        success: function (resp) {

            if (!resp.success) {
                Swal.fire('Error', resp.message || 'No se pudo obtener datos', 'error');
                return;
            }

            const e = resp.data;

            // FOTO
            if (e.foto) {
                $('#ver_foto').attr('src', e.foto);
            } else {
                $('#ver_foto').attr('src', '/assets/img/sin_foto.png');
            }

            // CAMPOS
            $('#ver_id').text(e.id);
            $('#ver_nombres').text(e.nombres);
            $('#ver_apellidos').text(e.apellidos);
            $('#ver_dni').text(e.dni);
            $('#ver_empresa').text(e.empresa);
            $('#ver_correo').text(e.correo);
            $('#ver_telefono').text(e.telefono);
            $('#ver_direccion').text(e.direccion);
            $('#ver_fecha_ingreso').text(e.fecha_ingreso);

            // ROLES (siempre array seguro)
            $('#ver_roles').text(
                (e.roles && Array.isArray(e.roles)) ? e.roles.join(", ") : "Sin roles"
            );

            // MOSTRAR MODAL
            const modal = new bootstrap.Modal(document.getElementById('modalVerEmpleado'));
            modal.show();
        },

        error: function (xhr) {
            console.error("ERROR AJAX VER:", xhr.responseText);
            Swal.fire('Error', 'Error inesperado al obtener datos', 'error');
        }
    });
};

$(document).on("click", ".btnVer", function () {
    const id = $(this).data("id");
    Empleados.abrirVer(id);
});

