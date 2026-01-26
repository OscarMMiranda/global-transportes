// archivo: /modulos/conductores/assets/datatables.js
// COMPONENTE DE TABLAS (namespace global)

window.Conductores = window.Conductores || {};

(function () {

    let tablaActivos = null;
    let tablaInactivos = null;

    /**
     * Genera los botones de acción por fila
     */
    function accionesHTML(r) {

        const verBtn = `
            <button class="btn btn-sm btn-outline-info btn-view" data-id="${r.id}">
                <i class="fa-solid fa-eye"></i>
            </button>`;

        const historialBtn = `
            <button class="btn btn-sm btn-outline-secondary btn-historial" data-id="${r.id}">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </button>`;

        // Conductor ACTIVO
        if (parseInt(r.activo) === 1) {
            return `
                ${verBtn}
                <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${r.id}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                ${historialBtn}
                <button class="btn btn-sm btn-outline-warning btn-soft-delete" data-id="${r.id}">
                    <i class="fa-solid fa-ban"></i>
                </button>`;
        }

        // Conductor INACTIVO
        return `
            ${verBtn}
            <button class="btn btn-sm btn-outline-success btn-restore" data-id="${r.id}">
                <i class="fa-solid fa-rotate-left"></i>
            </button>
            ${historialBtn}
            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${r.id}">
                <i class="fa-solid fa-trash"></i>
            </button>`;
    }

    /**
     * Crea una tabla DataTable
     */
    function crearTabla(selector, url) {

        return $(selector).DataTable({
            ajax: {
                url,
                dataSrc: json => json.success ? json.data : []
            },
            columns: [
                { data: null, render: (d, t, r, m) => m.row + 1 },
                { data: null, render: r => `<i class="fa-solid fa-user text-primary me-2"></i>${r.apellidos}, ${r.nombres}` },
                { data: 'dni' },
                { data: 'licencia_conducir' },
                { data: 'telefono' },
                { data: 'activo', render: d => d == 1 ? 'Activo' : 'Inactivo' },
                { data: null, render: accionesHTML }
            ],
            responsive: true,
            autoWidth: false,
            pageLength: 10
        });
    }

    /**
     * Inicializa tabla de activos
     */
    window.Conductores.initTablaActivos = function () {
        tablaActivos = crearTabla('#tblActivos', '/modulos/conductores/acciones/listar.php?estado=activo');
        window.Conductores.tablaActivos = tablaActivos;
    };

    /**
     * Inicializa tabla de inactivos
     */
    window.Conductores.initTablaInactivos = function () {
        tablaInactivos = crearTabla('#tblInactivos', '/modulos/conductores/acciones/listar.php?estado=inactivo');
        window.Conductores.tablaInactivos = tablaInactivos;
    };

})();