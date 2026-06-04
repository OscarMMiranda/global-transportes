// archivo: /modulos/orden_trabajo/js/filtros.js

$(document).ready(function () {

    // Cargar datos al iniciar
    cargarTabActivo();

    // Cuando cambia la semana
    $("#filtroSemana").on("change", function () {
        cargarTabActivo();
    });

    // Cuando cambia de tab
    $('[data-bs-toggle="tab"]').on("shown.bs.tab", function () {
        cargarTabActivo();
    });
});

// ===============================
// 🔵 SANITIZAR TEXTO
// ===============================
function escapeHtml(texto) {
    if (!texto) return "";
    return texto
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

// ===============================
// 🔵 OBTENER TAB ACTIVO
// ===============================
function obtenerTabActivo() {

    let activo = $(".tab-pane.active.show").attr("id")
        || $(".tab-pane.show.active").attr("id")
        || $(".tab-pane.active").attr("id")
        || $(".tab-pane.show").attr("id");

    return activo;
}

// ===============================
// 🔵 CARGAR TAB ACTIVO
// ===============================
function cargarTabActivo() {

    let tab = obtenerTabActivo();
    let semana = $("#filtroSemana").val();

    let tbody = "";
    let estado = "";

    if (tab === "activas") {
        tbody = "#tbodyActivas";
        estado = "ACTIVA";
    }
    else if (tab === "anuladas") {
        tbody = "#tbodyAnuladas";
        estado = "ANULADA";
    }
    else if (tab === "eliminadas") {
        tbody = "#tbodyEliminadas";
        estado = "ELIMINADA";
    }

    // Spinner
    $(tbody).html(
        '<tr><td colspan="10" class="text-center py-4">' +
        '<div class="spinner-border text-primary"></div>' +
        '<p class="mt-2">Cargando...</p>' +
        '</td></tr>'
    );

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/ListController.php",
        type: "GET",
        data: {
            semana: semana,
            estado: estado,
            ajax: 1
        },
        dataType: "json",

        success: function (res) {

            let html = "";

            if (!res || res.length === 0) {
                html = '<tr><td colspan="10" class="text-center">No hay registros</td></tr>';
            } else {

                $.each(res, function (i, ot) {

                    html += "<tr class='text-center'>" +
                        "<td class='fw-bold'>" + escapeHtml(ot.numero_ot) + "</td>" +
                        "<td>" + escapeHtml(ot.fecha) + "</td>" +
                        "<td>" + escapeHtml(ot.cliente_nombre) + "</td>" +
                        "<td>" + escapeHtml(ot.oc_cliente) + "</td>" +
                        "<td>" + escapeHtml(ot.tipo_ot_nombre) + "</td>" +
                        "<td>" + escapeHtml(ot.empresa_nombre) + "</td>" +
                        "<td>" + escapeHtml(ot.estado_nombre) + "</td>" +
                        "<td class='text-center'>" +
                            "<button class='btn btn-info btn-sm me-1' onclick='editarOT(" + ot.id + ")'><i class=\"fa-solid fa-pen-to-square\"></i></button>" +
                            "<button class='btn btn-warning btn-sm me-1' onclick='anularOT(" + ot.id + ")'><i class=\"fa-solid fa-ban\"></i></button>" +
                            "<button class='btn btn-danger btn-sm' onclick='eliminarOT(" + ot.id + ")'><i class=\"fa-solid fa-trash\"></i></button>" +
                        "</td>" +
                    "</tr>";
                });
            }

            $(tbody).html(html);
        },

        error: function (xhr, status, error) {
            console.error("❌ Error AJAX:", status, error);
            console.log(xhr.responseText);

            $(tbody).html(
                '<tr><td colspan="10" class="text-center text-danger">Error al cargar datos</td></tr>'
            );
        }
    });
}
