// DataTables para Conductores
// ----------------------------------------------
// archivo: modulos/conductores/assets/datatables.js

$(function () {

    const listarActivosApi   = '/modulos/conductores/acciones/listar.php?estado=activo';
    const listarInactivosApi = '/modulos/conductores/acciones/listar.php?estado=inactivo';

    let tablaActivos   = null;
    let tablaInactivos = null;

    // --------------------------------------------------------------
    // Botones de acción
    // --------------------------------------------------------------
    function accionesHTML(r) {

        const verBtn = `
            <button class="btn btn-sm btn-outline-info btn-view" data-id="${r.id}" title="Ver">
                <i class="fa-solid fa-eye"></i>
            </button>`;

        if (parseInt(r.activo) === 1) {
            return `
                ${verBtn}
                <button class="btn btn-sm btn-outline-primary btn-edit" data-id="${r.id}" title="Editar">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning btn-soft-delete" data-id="${r.id}" title="Desactivar">
                    <i class="fa-solid fa-ban"></i>
                </button>`;
        }

        return `
            ${verBtn}
            <button class="btn btn-sm btn-outline-success btn-restore" data-id="${r.id}" title="Restaurar">
                <i class="fa-solid fa-rotate-left"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${r.id}" title="Eliminar definitivo">
                <i class="fa-solid fa-trash"></i>
            </button>`;
    }

    // --------------------------------------------------------------
    // Inicializador universal de tablas
    // --------------------------------------------------------------
    function initTable(selector, ajaxUrl) {

        if (!$(selector).length) return null;

        const tablaId = selector.replace('#', '');
        const loader  = document.getElementById(`loader-${tablaId}`);

        const tabla = $(selector).DataTable({
            ajax: {
                url: ajaxUrl,
                dataSrc: json => json.success ? json.data : []
            },
            columns: [
                { data: null, render: (d,t,r,m) => m.row + 1 },
                { data: null, render: r => `<i class="fa-solid fa-user text-primary me-2"></i>${r.apellidos}, ${r.nombres}` },
                { data: 'dni', render: d => `<span class="font-monospace">${d}</span>` },
                { data: 'licencia_conducir', render: d => `<span class="badge bg-info text-dark">${d}</span>` },
                { data: 'telefono', render: d => d ? `<i class="fa-solid fa-phone me-1 text-success"></i>${d}` : '—' },
                { 
                    data: 'activo',
                    render: d => d == 1 
                        ? '<span class="badge-activo">Activo</span>' 
                        : '<span class="badge-inactivo">Inactivo</span>'
                },
                { data: null, render: accionesHTML }
            ],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
            pageLength: 10,
            responsive: true,
            autoWidth: false,
            order: [[1, 'asc']]
        });

        // Loader
        $(selector).on('processing.dt', function (e, settings, processing) {
            if (loader) loader.classList.toggle('d-none', !processing);
        });

        return tabla;
    }

    // --------------------------------------------------------------
    // Inicializar tablas (guardar instancias)
    // --------------------------------------------------------------
    tablaActivos   = initTable('#tblActivos', listarActivosApi);
    tablaInactivos = initTable('#tblInactivos', listarInactivosApi);

    // --------------------------------------------------------------
    // FIX DEFINITIVO: ajustar columnas al mostrar tab
    // --------------------------------------------------------------
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {

        const target = $(e.target).attr('data-bs-target');

        if (target === '#panel-inactivos' && tablaInactivos) {
            tablaInactivos.columns.adjust().draw(false);
        }

        if (target === '#panel-activos' && tablaActivos) {
            tablaActivos.columns.adjust().draw(false);
        }
    });

    // API pública
    window.ConductoresDT = {
        reloadActivos:   () => tablaActivos.ajax.reload(null, false),
        reloadInactivos: () => tablaInactivos.ajax.reload(null, false)
    };

    console.log('🚚 datatables.js inicializado correctamente');
});