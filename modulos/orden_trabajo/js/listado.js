// archivo: /modulos/orden_trabajo/js/listado.js
// ============================================================
// LISTADO DE ORDENES DE TRABAJO (OPTIMIZADO)
// ============================================================

console.log("LISTADO JS CARGADO");

$(document).ready(function () {

    let estadoActual = "TODAS";

    // ============================================================
    // FILTRO POR ESTADO
    // ============================================================
    $(".btn-estado").on("click", function (e) {
        e.preventDefault();

        $(".btn-estado").removeClass("active");
        $(this).addClass("active");

        estadoActual = $(this).data("estado");

        tablaOT.ajax.reload(null, false);
    });

    // ============================================================
    // FILTRO POR SEMANA
    // ============================================================
    $("#filtro_semana").on("change", function () {
        tablaOT.ajax.reload(null, false);
    });

    // ============================================================
    // DATATABLE PRINCIPAL
    // ============================================================
    window.tablaOT = $("#tablaOT").DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "controllers/ListController.php",
            type: "POST",
            data: function (d) {
                d.ajax = 1;
                d.estado = estadoActual;
                d.semana = $("#filtro_semana").val();
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo cargar el listado de órdenes."
                });
            }
        },
        columns: [
            { data: null, render: (d, t, r, m) => m.row + 1 },
            { data: "numero_ot" },
            { data: "fecha" },
            { data: "cliente" },
            { data: "oc_cliente" },
            { data: "tipo_ot" },
            { data: "empresa" },
            { data: "numero_viajes" },
            { data: "estado" },

            // ============================================================
            // ACCIONES CORPORATIVAS
            // ============================================================
            {
                data: "id",
                render: id => `
                    <div class="btn-group btn-group-sm">

                        <!-- VER OV -->
                        <button class="btn btn-outline-primary btn-ver" data-id="${id}">
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        <!-- EDITAR OT -->
                        <button class="btn btn-outline-info btn-editar" data-id="${id}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <!-- LOGÍSTICA -->
                        <button class="btn btn-outline-warning btn-logistica" data-id="${id}">
                            <i class="fa-solid fa-truck"></i>
                        </button>

                    </div>
                `
            }
        ],
        language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" }
    });

    // ============================================================
    // EVENTO: VER OV
    // ============================================================
    // $("#tablaOT").on("click", ".btn-ver", function () {
    //     verOV($(this).data("id"));
    // });

    // ============================================================
    // EVENTO: EDITAR OT
    // ============================================================
    $("#tablaOT").on("click", ".btn-editar", function () {
        editarOT($(this).data("id"));
    });

    // ============================================================
    // EVENTO: LOGÍSTICA
    // ============================================================
    $("#tablaOT").on("click", ".btn-logistica", function () {
        abrirLogistica($(this).data("id"));
    });

});
