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

            console.log("DATA RECIBIDA:", resp);

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
        console.log("CLICK EN EDITAR", id);

        $.get(`/modulos/conductores/acciones/obtener.php?id=${id}`, function (resp) {

            console.log("DATA RECIBIDA:", resp);

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

            // FOTO ACTUAL → para que guardar.php la conserve si no se sube una nueva
            $('#c_foto_actual').val(c.foto || '');

            // UBIGEO
            Ubigeo.cargar(
                '#departamento_id',
                '#provincia_id',
                '#distrito_id',
                {
                    departamento_id: c.departamento_id,
                    provincia_id: c.provincia_id,
                    distrito_id: c.distrito_id
                }
            );
        }, 'json');
    });

    // DESACTIVAR
    $(document).on('click', '.btn-soft-delete', function () {
        const id = $(this).data('id');

        $.post(`/modulos/conductores/acciones/desactivar.php?id=${id}`, function (resp) {

            console.log("RESPUESTA DESACTIVAR:", resp);

            if (resp.success) {
                Swal.fire('Desactivado', 'El conductor fue desactivado correctamente', 'success');
            } else {
                Swal.fire('Error', resp.error || 'No se pudo desactivar', 'error');
            }

            Conductores.tablaActivos.ajax.reload(null, false);
            Conductores.tablaInactivos.ajax.reload(null, false);

        }, 'json');
    });

    // RESTAURAR
    $(document).on('click', '.btn-restore', function () {
        const id = $(this).data('id');

        $.post(`/modulos/conductores/acciones/restaurar.php?id=${id}`, function (resp) {

            console.log("RESPUESTA RESTAURAR:", resp);

            if (resp.success) {
                Swal.fire('Restaurado', 'El conductor fue restaurado correctamente', 'success');
            } else {
                Swal.fire('Error', resp.error || 'No se pudo restaurar', 'error');
            }

            Conductores.tablaActivos.ajax.reload(null, false);
            Conductores.tablaInactivos.ajax.reload(null, false);

        }, 'json');
    });

    // ELIMINAR DEFINITIVO
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

                console.log("RESPUESTA ELIMINAR:", resp);

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

// VER HISTORIAL
$(document).on('click', '.btn-historial', function () {
    const id = $(this).data('id');

    $.get(`/modulos/conductores/acciones/obtener_historial.php?id=${id}`, function (resp) {

        if (!resp.success) {
            Swal.fire('Error', resp.error, 'error');
            return;
        }

        const historial = resp.historial;
        let html = '';

        historial.forEach(h => {

            html += `
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Acción:</strong> ${h.accion.toUpperCase()}  
                        <span class="float-end"><strong>${h.fecha_cambio}</strong></span>
                    </div>

                    <div class="card-body">

                        <p><strong>Usuario:</strong> ${h.usuario || '—'}</p>

                        <p><strong>Cambios:</strong></p>
                        <pre class="bg-dark text-white p-2 rounded">${JSON.stringify(h.cambios_json, null, 2)}</pre>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>Foto actual:</strong><br>
                                ${h.foto ? `<img src="/uploads/conductores/${h.foto}" class="img-fluid rounded border">` : '—'}
                            </div>

                            <div class="col-md-6">
                                <strong>Foto anterior:</strong><br>
                                ${h.ruta_foto_anterior ? `<img src="${h.ruta_foto_anterior.replace($_SERVER['DOCUMENT_ROOT'], '')}" class="img-fluid rounded border">` : '—'}
                            </div>
                        </div>

                    </div>
                </div>
            `;
        });

        $('#contenedorHistorial').html(html);
        $('#modalHistorialConductor').modal('show');
    }, 'json');
});


// ============================================================
// GUARDAR (CREAR / EDITAR) — ENVÍO REAL DE ARCHIVOS
// ============================================================
$(document).on('click', '#btnGuardarConductor', function (e) {
    e.preventDefault();

    let formData = new FormData($('#formConductor')[0]);

    $.ajax({
        url: '/modulos/conductores/acciones/guardar.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',

        success: function (resp) {
            if (resp.success) {
                Swal.fire('Éxito', 'Datos guardados correctamente', 'success');
                $('#modalConductor').modal('hide');

                Conductores.tablaActivos.ajax.reload(null, false);
                Conductores.tablaInactivos.ajax.reload(null, false);
            } else {
                Swal.fire('Error', resp.error || 'No se pudo guardar', 'error');
            }
        },

        error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire('Error', 'Error inesperado al guardar', 'error');
        }
    });
});
