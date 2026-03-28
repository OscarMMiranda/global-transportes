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

        cargarSelectConActual(API.conductoresDisponibles(), '[data-role="editar-conductor"]', data.conductor_id);
        cargarSelectConActual(API.tractosDisponibles(), '[data-role="editar-tracto"]', data.tracto_id);
        cargarSelectConActual(API.carretasDisponibles(), '[data-role="editar-carreta"]', data.carreta_id);

    }).fail(() => {
        alert("Error al obtener datos de la asignación.");
    });
}
