// archivo: /modulos/clientes/assets/datatables.js
// Configuración corporativa global para DataTables

if (typeof $.fn.dataTable !== "undefined") {

    $.extend(true, $.fn.dataTable.defaults, {

        // Diseño corporativo
        responsive: true,
        processing: true,
        deferRender: true,

        // Ordenamiento por defecto
        order: [],

        // Paginación
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],

        // Idioma corporativo
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },

        // Layout corporativo
        dom:
            "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>"
    });

}
