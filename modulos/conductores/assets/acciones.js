// archivo: /modulos/conductores/assets/acciones.js
// EVENTOS DE ACCIONES DEL MÓDULO CONDUCTORES

window.Conductores = window.Conductores || {};

(function () {

    // ============================================================
    // VER CONDUCTOR
    // ============================================================
    $(document).on('click', '.btn-view', function () {

        const id = $(this).data('id');
        console.log("CLICK EN VER", id);

        $.get(`/modulos/conductores/acciones/obtener.php?id=${id}`, function (resp) {

            if (!resp || resp.success !== true || !resp.data) {
                console.error("❌ Respuesta inválida:", resp);
                return;
            }

            const c = resp.data;

            $('#modalVerConductor').modal('show');

            $('#ver_nombre').text(`${c.nombres} ${c.apellidos}`);
            $('#ver_dni').text(c.dni);
            $('#ver_licencia').text(c.licencia_conducir);
            $('#ver_telefono').text(c.telefono || '—');
            $('#ver_correo').text(c.correo || '—');
            $('#ver_direccion').text(c.direccion || '—');

            $('#ver_departamento').text(c.departamento_nombre || '—');
            $('#ver_provincia').text(c.provincia_nombre || '—');
            $('#ver_distrito').text(c.distrito_nombre || '—');

            if (c.activo == 1) {
                $('#ver_estado')
                    .text('Activo')
                    .removeClass()
                    .addClass('badge rounded-pill bg-success');
            } else {
                $('#ver_estado')
                    .text('Inactivo')
                    .removeClass()
                    .addClass('badge rounded-pill bg-secondary');
            }

            if (c.foto) {
                $('#ver_foto').attr('src', c.foto).show();
                $('#sin_foto').hide();
            } else {
                $('#ver_foto').hide();
                $('#sin_foto').show();
            }
        }, 'json');
    });

    // ============================================================
    // EDITAR CONDUCTOR
    // ============================================================
    $(document).on('click', '.btn-edit', function () {

        const id = $(this).data('id');

        $.get(`/modulos/conductores/acciones/obtener.php?id=${id}`, function (resp) {

            if (!resp || resp.success !== true || !resp.data) {
                console.error("❌ Respuesta inválida:", resp);
                return;
            }

            const c = resp.data;

            $('#modalConductor').attr('data-modo', 'editar');

            $('#tituloModalConductor').html(
                `<i class="fa fa-id-card me-2"></i> Editar Conductor`
            );

            $('#btnGuardarConductor').html(
                `<i class="fa fa-save"></i> Guardar Cambios`
            );

            $('#modalConductor').modal('show');

            $('#c_id').val(c.id);
            $('#c_nombres').val(c.nombres);
            $('#c_apellidos').val(c.apellidos);
            $('#c_dni').val(c.dni);
            $('#c_licencia').val(c.licencia_conducir);
            $('#c_correo').val(c.correo);
            $('#c_telefono').val(c.telefono);
            $('#c_direccion').val(c.direccion);

            $('#c_activo').prop('checked', c.activo == 1);

            if (c.foto) {
                $('#preview_foto').attr('src', c.foto).show();
            } else {
                $('#preview_foto').hide();
            }

            $('#c_foto_actual').val(c.foto || '');

            // UBIGEO
            if (c.departamento_id && c.provincia_id && c.distrito_id) {
                Ubigeo.cargar('#departamento_id', '#provincia_id', '#distrito_id', {
                    departamento_id: c.departamento_id,
                    provincia_id: c.provincia_id,
                    distrito_id: c.distrito_id
                });
            } else {
                Ubigeo.cargar('#departamento_id', '#provincia_id', '#distrito_id');
            }

            setTimeout(function () {
                $('#empresa_id').val(c.empresa_id);
            }, 300);

        }, 'json');
    });

    // ============================================================
    // DESACTIVAR
    // ============================================================
    $(document).on('click', '.btn-soft-delete', function () {
        const id = $(this).data('id');

        $.post(`/modulos/conductores/acciones/desactivar.php?id=${id}`, function (resp) {

            if (resp.success) {
                Swal.fire('Desactivado', 'El conductor fue desactivado correctamente', 'success');
            } else {
                Swal.fire('Error', resp.error || 'No se pudo desactivar', 'error');
            }

            Conductores.tablaActivos.ajax.reload(null, false);
            Conductores.tablaInactivos.ajax.reload(null, false);

        }, 'json');
    });

    // ============================================================
    // RESTAURAR
    // ============================================================
    $(document).on('click', '.btn-restore', function () {
        const id = $(this).data('id');

        $.post(`/modulos/conductores/acciones/restaurar.php?id=${id}`, function (resp) {

            if (resp.success) {
                Swal.fire('Restaurado', 'El conductor fue restaurado correctamente', 'success');
            } else {
                Swal.fire('Error', resp.error || 'No se pudo restaurar', 'error');
            }

            Conductores.tablaActivos.ajax.reload(null, false);
            Conductores.tablaInactivos.ajax.reload(null, false);

        }, 'json');
    });

    // ============================================================
    // ELIMINAR DEFINITIVO
    // ============================================================
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar definitivamente?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (!result.isConfirmed) return;

            $.post(`/modulos/conductores/acciones/eliminar.php?id=${id}`, function (resp) {

                if (resp.success) {
                    Swal.fire('Eliminado', 'El conductor fue eliminado permanentemente', 'success');
                } else {
                    Swal.fire('Error', resp.error || 'No se pudo eliminar', 'error');
                }

                Conductores.tablaActivos.ajax.reload(null, false);
                Conductores.tablaInactivos.ajax.reload(null, false);

            }, 'json');
        });
    });

})();

// ============================================================
// FUNCIÓN UNIVERSAL PARA ABRIR HISTORIAL
// ============================================================
function abrirHistorial(tabla, id) {

    $("#historialContenido").html(`
        <div class="text-center text-muted py-4">
            Cargando historial...
        </div>
    `);

    const modal = new bootstrap.Modal(document.getElementById('modalHistorial'));
    modal.show();

    $.ajax({
        url: `/modulos/${tabla}/acciones/historial.php`,
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function(resp) {
            $("#historialContenido").html(resp.html);
        },
        error: function() {
            $("#historialContenido").html(`
                <div class="alert alert-danger">
                    Error al cargar historial.
                </div>
            `);
        }
    });
}

// ============================================================
// HISTORIAL  ← ESTE BLOQUE DEBE ESTAR FUERA
// ============================================================
$(document).on('click', '.btn-historial', function () {
    const id = $(this).data('id');
    const tabla = $(this).data('tabla');
    abrirHistorial(tabla, id);
});
