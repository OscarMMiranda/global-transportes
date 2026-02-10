// archivo: /modulos/asistencias/js/listar_asistencias.js
// módulo: asistencias
// propósito: cargar la tabla de asistencias con DataTables y AJAX

console.log("listar_asistencias.js CARGADO REALMENTE");

document.addEventListener("DOMContentLoaded", function () {

    const tabla = document.getElementById('tablaAsistencias');
    if (!tabla) return;

    // Si la tabla ya estaba inicializada, destruirla antes de volver a crearla
    if ($.fn.DataTable.isDataTable('#tablaAsistencias')) {
        $('#tablaAsistencias').DataTable().destroy();
    }

    $('#tablaAsistencias').DataTable({
        ajax: '../acciones/listar_asistencias.php',
        processing: true,
        serverSide: false,
        responsive: true,
        pageLength: 25,

        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },

        columns: [
            { data: 'fecha' },
            { data: 'conductor' },
            { data: 'tipo' },
            { data: 'hora_entrada' },
            { data: 'hora_salida' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (id) {
                    return `
                        <button class="btn btn-warning btn-sm btnEditarAsistencia"
                                data-id="${id}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    `;
                }
            }
        ]
    });

});
