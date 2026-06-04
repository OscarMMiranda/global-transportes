// archivo: /modulos/asignaciones/js/asignaciones.modals.js
// Manejo de modales del módulo Asignaciones

function initModalesAsignaciones() {

    /* ============================================================
       MODAL ASIGNAR
       ============================================================ */
    $('#modalAsignar').on('shown.bs.modal', function () {

        cargarSelect(API.conductoresDisponibles(), '[data-role="conductor"]');
        cargarSelect(API.tractosDisponibles(), '[data-role="tracto"]');
        cargarSelect(API.carretasDisponibles(), '[data-role="carreta"]');
    });

    $('#formAsignacion').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(API.guardar(), {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.ok) {
                $('#modalAsignar').modal('hide');
                window.tabla.ajax.reload();
            } else {
                alert('Error al guardar: ' + resp.error);
            }
        })
        .catch(err => alert('Fallo en la petición: ' + err));
    });



    /* ============================================================
       MODAL FINALIZAR
       ============================================================ */
    $('#formFinalizar').on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch(API.finalizar(), {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.ok) {
                $('#modalFinalizar').modal('hide');
                window.tabla.ajax.reload();
            } else {
                alert('Error al finalizar: ' + resp.error);
            }
        })
        .catch(err => alert('Fallo en la petición: ' + err));
    });
}



/* ============================================================
   MODAL DETALLE
   ============================================================ */
function abrirModalDetalle(id) {

    $('#detalleContenido').html('<p>Cargando...</p>');
    $('#modalDetalle').modal('show');

    $.getJSON(API.obtener(id), function (data) {

        if (!data) {
            $('#detalleContenido').html('<p>Error al cargar datos.</p>');
            return;
        }

        const html = `
            <div><strong>Conductor:</strong> ${data.conductor}</div>
            <div><strong>Tracto:</strong> ${data.tracto}</div>
            <div><strong>Carreta:</strong> ${data.carreta}</div>
            <div><strong>Inicio:</strong> ${data.inicio}</div>
            <div><strong>Fin:</strong> ${data.fin ?? '—'}</div>
            <div><strong>Estado:</strong> ${data.estado}</div>
        `;

        $('#detalleContenido').html(html);

    }).fail(() => {
        $('#detalleContenido').html('<p>Error al obtener datos.</p>');
    });
}



/* ============================================================
   MODAL EDITAR
   ============================================================ */
function abrirModalEditar(id) {

    $('#editar_id').val(id);
    $('#modalEditar').modal('show');

    $.getJSON(API.obtener(id), function (data) {

        if (!data) {
            alert("No se pudo cargar la asignación.");
            return;
        }

        $('#editar_inicio').val(data.inicio);

        cargarSelectConActual(
    API.conductoresDisponibles(),
    '[data-role="editar-conductor"]',
    'conductor',
    data.conductor_id,
    data.conductor
);

cargarSelectConActual(
    API.tractosDisponibles(),
    '[data-role="editar-tracto"]',
    'tracto',
    data.tracto_id,
    data.tracto
);

cargarSelectConActual(
    API.carretasDisponibles(),
    '[data-role="editar-carreta"]',
    'carreta',
    data.carreta_id,
    data.carreta
);

    }).fail(() => {
        alert("Error al obtener datos de la asignación.");
    });
}

/* ============================================================
   GUARDAR EDICIÓN
   ============================================================ */
$('#formEditar').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(API.editar(), {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.ok) {
            $('#modalEditar').modal('hide');
            window.tabla.ajax.reload();
        } else {
            alert('Error al editar: ' + resp.error);
        }
    })
    .catch(err => alert('Fallo en la petición: ' + err));
});

function abrirModalReasignar(id) {

    $('#reasignar_id').val(id);
    $('#modalReasignar').modal('show');

    $.getJSON(API.obtener(id), function (data) {

        cargarSelectConActual(
            API.conductoresDisponibles(),
            '[data-role="reasignar-conductor"]',
            'conductor',
            data.conductor_id,
            data.conductor
        );

        cargarSelectConActual(
            API.tractosDisponibles(),
            '[data-role="reasignar-tracto"]',
            'tracto',
            data.tracto_id,
            data.tracto
        );

        cargarSelectConActual(
            API.carretasDisponibles(),
            '[data-role="reasignar-carreta"]',
            'carreta',
            data.carreta_id,
            data.carreta
        );

    });
}

/* ============================================================
   MODAL HISTORIAL
   ============================================================ */
function abrirModalHistorial(id) {

    // Mensaje inicial
    $('#historialContenido').html('<p>Cargando historial...</p>');
    $('#modalHistorial').modal('show');

    // Llamada AJAX
    $.getJSON(API.historial(id), function (data) {

        // Si no hay registros
        if (!data || data.length === 0) {
            $('#historialContenido').html('<p>No hay historial registrado.</p>');
            return;
        }

        // Construcción de tabla
        let html = `
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Acción</th>
                        <th>Estado anterior</th>
                        <th>Estado nuevo</th>
                        <th>Motivo</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
        `;

        data.forEach(item => {
            html += `
                <tr>
                    <td>${item.fecha ?? '—'}</td>
                    <td>${item.accion ?? '—'}</td>
                    <td>${item.estado_anterior ?? '—'}</td>
                    <td>${item.estado_nuevo ?? '—'}</td>
                    <td>${item.motivo ?? '—'}</td>
                    <td>${item.usuario_id ?? '—'}</td>
                    <td>${item.rol_usuario ?? '—'}</td>
                    <td>${item.ip_origen ?? '—'}</td>
                </tr>
            `;
        });

        html += `</tbody></table>`;

        // Render final
        $('#historialContenido').html(html);

    }).fail(() => {
        $('#historialContenido').html('<p>Error al cargar historial.</p>');
    });
}


/* ===== GUARDAR REASIGNACIÓN ===== */
$(document).on('submit', '#formReasignar', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(API.reasignar(), {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.ok) {
            $('#modalReasignar').modal('hide');
            window.tabla.ajax.reload();
        } else {
            alert('Error al reasignar: ' + resp.error);
        }
    })
    .catch(err => alert('Fallo en la petición: ' + err));
});
