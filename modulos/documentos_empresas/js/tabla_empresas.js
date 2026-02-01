//  archivo: /modulos/documentos_empresas/js/tabla_empresas.js

$(document).ready(function() {
    $('#tablaEmpresas').DataTable({
        ajax: {
            url: "/modulos/documentos_empresas/acciones/listar_empresas.php",
            type: "GET",
            dataSrc: "data"
        },
        columns: [
            { data: "razon_social" },
            { data: "ruc" },
            { data: "total_documentos" },
            { 
                data: "por_vencer",
                render: function(v) {
                    if (v > 0) {
                        return "<span class='badge bg-warning text-dark'>" + v + "</span>";
                    }
                    return "<span class='badge bg-secondary'>0</span>";
                }
            },
            { data: "estado" },
            { data: "acciones" }
        ],
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
});
