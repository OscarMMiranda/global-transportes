// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/js/listado.js
//  RESPONSABILIDAD: Control del listado con DataTables
// ======================================================

$(document).ready(function() {

    let estadoActual = "ACTIVA";

    $(".btn-estado").on("click", function() {
        estadoActual = $(this).data("estado");
        tabla.ajax.reload();
    });

    $("#filtro_semana").on("change", function() {
        tabla.ajax.reload();
    });

    const tabla = $("#tablaOT").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "controllers/ListController.php",
            type: "POST",
            data: function(d) {
                d.ajax = 1;
                d.estado = estadoActual;
                d.semana = $("#filtro_semana").val();
            }
        },
        columns: [
            { data: "id" },
            { data: "numero_ot" },
            { data: "fecha" },
            { data: "semana" },
            { data: "cliente" },
            { data: "vehiculo" },
            { data: "tipo_ot" },
            { data: "estado" },
            {
                data: "id",
                render: function(id) {
                    return `
                        <button class="btn btn-sm btn-primary btn-editar" data-id="${id}">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="${id}">
                            <i class="fa fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });

});
