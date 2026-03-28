// archivo: /modulos/asignaciones/js/asignaciones.table.js
// Tabla principal de asignaciones

function initTablaAsignaciones() {

    return $('#tablaAsignaciones').DataTable({

        processing: true,
        serverSide: false,
        responsive: true,

        ajax: {
            url: API.listar(),
            type: 'GET',

            // Enviar filtros al backend
            data: function () {
                return obtenerParametrosFiltros();
            },

            // Validación de JSON
            dataSrc: function (json) {
                if (!Array.isArray(json)) {
                    console.error("JSON inválido recibido:", json);
                    alert("Error: El servidor devolvió un formato inválido.");
                    return [];
                }
                return json;
            }
        },

        columns: [

            { data: 'conductor' },
            { data: 'tracto' },
            { data: 'carreta' },

            {
                data: 'inicio',
                render: f => f ? new Date(f).toLocaleDateString() : ''
            },

            {
                data: 'fin',
                render: f => f ? new Date(f).toLocaleDateString() : ''
            },

            { data: 'estado' },

            // ============================================================
            // COLUMNA DE ACCIONES
            // ============================================================
            {
                data: 'id',
                orderable: false,
                render: (id, _, row) => {

                    const btnDetalle = `
                        <button class="btn btn-info btn-sm btn-detalle" data-id="${id}">
                            <i class="bi bi-eye"></i>
                        </button>`;

                    const btnEditar = row.estado === 'activo'
                        ? `<button class="btn btn-primary btn-sm btn-editar" data-id="${id}">
                                <i class="bi bi-pencil-square"></i>
                           </button>`
                        : '';

                    const btnReasignar = row.estado === 'activo'
                        ? `<button class="btn btn-secondary btn-sm btn-reasignar" data-id="${id}">
                                <i class="bi bi-arrow-repeat"></i>
                           </button>`
                        : '';

                    const btnHistorial = `
                        <button class="btn btn-outline-info btn-sm btn-historial" data-id="${id}">
                            <i class="bi bi-clock-history"></i>
                        </button>`;

                    const btnFinalizar = row.estado === 'activo'
                        ? `<button class="btn btn-warning btn-sm btn-finalizar" data-id="${id}">
                                <i class="bi bi-x-circle"></i>
                           </button>`
                        : `<span class="text-muted">—</span>`;

                    return `
                        <div class="btn-group" role="group">
                            ${btnDetalle}
                            ${btnEditar}
                            ${btnReasignar}
                            ${btnHistorial}
                            ${btnFinalizar}
                        </div>
                    `;
                }
            }
        ]
    });
}
