// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/js/listado.js
// ======================================================

$(document).ready(function () {

    let estadoActual = "ACTIVA";

    // ============================
    //  TABS DE ESTADO
    // ============================
    $(".btn-estado").on("click", function () {

        $(".btn-estado").removeClass("active");
        $(this).addClass("active");

        estadoActual = $(this).data("estado");

        tablaOT.ajax.reload();
    });

    // ============================
    //  FILTRO DE SEMANA
    // ============================
    $("#filtro_semana").on("change", function () {
        tablaOT.ajax.reload();
    });

    // ============================
    //  DATATABLE CORPORATIVO
    // ============================
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
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1; // CORRELATIVO VISUAL
                }
            },
            { data: "numero_ot" },
            { data: "fecha" },
            { data: "semana" },
            { data: "cliente" },
            { data: "oc_cliente" },
            { data: "tipo_ot" },
            { data: "empresa" },
            { data: "numero_viajes" },
            { data: "estado" },

            // ============================
            //  ACCIONES CORPORATIVAS
            // ============================
            {
                data: "id",
                render: function (id) {

                    return `
                        <div class="btn-group">

                            <!-- VER -->
                            <button class="btn btn-sm btn-outline-secondary btn-ver" data-id="${id}">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <!-- EDITAR -->
                            <button class="btn btn-sm btn-outline-primary btn-editar" data-id="${id}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- GESTIONAR -->
                            <button class="btn btn-sm btn-outline-success btn-gestionar" data-id="${id}">
                                <i class="fa-solid fa-list-check"></i>
                            </button>

                            <!-- MENÚ MÁS ACCIONES -->
                            <button class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item btn-anular" data-id="${id}">
                                        <i class="fa-solid fa-ban"></i> Anular
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item btn-eliminar" data-id="${id}">
                                        <i class="fa-solid fa-trash"></i> Eliminar
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item btn-imprimir" data-id="${id}">
                                        <i class="fa-solid fa-print"></i> Imprimir
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item btn-exportar" data-id="${id}">
                                        <i class="fa-solid fa-file-excel"></i> Exportar
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item btn-historial" data-id="${id}">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Historial
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item btn-estado" data-id="${id}">
                                        <i class="fa-solid fa-flag"></i> Estado
                                    </a>
                                </li>

                            </ul>

                        </div>
                    `;
                }
            }
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });

});

// ============================
//  VER OT
// ============================
$("#tablaOT").on("click", ".btn-ver", function () {

    let id = $(this).data("id");

    $("#modalVerOTBody").html("<div class='p-3 text-center'>Cargando...</div>");

    $.post("controllers/VerController.php", { id }, function (html) {
        $("#modalVerOTBody").html(html);
        $("#modalVerOT").modal("show");
    });
});
