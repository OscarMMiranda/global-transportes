// archivo: /modulos/orden_trabajo/js/ordenes.js

$(document).ready(function () {
    cargarTablaOT();
});

function cargarTablaOT() {

    $("#tablaOT tbody").html(
        '<tr><td colspan="10" class="text-center py-4">' +
        '<div class="spinner-border text-primary"></div>' +
        '<p class="mt-2">Cargando órdenes...</p>' +
        '</td></tr>'
    );

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/ListController.php",
        type: "GET",
        dataType: "json",

        success: function (res) {

            var html = "";

            if (!res || res.length === 0) {
                html = '<tr><td colspan="10" class="text-center">No hay órdenes registradas</td></tr>';
            } else {

                $.each(res, function (i, ot) {

                    html += "<tr>" +
                        "<td>" + escapeHtml(ot.numero_ot) + "</td>" +
                        "<td>" + escapeHtml(ot.fecha) + "</td>" +
                        "<td>" + escapeHtml(ot.cliente) + "</td>" +
                        "<td>" + escapeHtml(ot.tipo_ot) + "</td>" +
                        "<td>" + escapeHtml(ot.empresa) + "</td>" +
                        "<td>" + escapeHtml(ot.estado) + "</td>" +

                        "<td class='text-center'>" +
                        "<button class='btn btn-info btn-sm me-1' onclick='editarOT(" + ot.id + ")'>" +
                        "<i class=\"fa-solid fa-pen-to-square\"></i></button>" +

                        "<button class='btn btn-warning btn-sm me-1' onclick='anularOT(" + ot.id + ")'>" +
                        "<i class=\"fa-solid fa-ban\"></i></button>" +

                        "<button class='btn btn-danger btn-sm' onclick='eliminarOT(" + ot.id + ")'>" +
                        "<i class=\"fa-solid fa-trash\"></i></button>" +
                        "</td>" +
                        "</tr>";
                });
            }

            $("#tablaOT tbody").html(html);
        },

        error: function () {
            $("#tablaOT tbody").html(
                '<tr><td colspan="10" class="text-center text-danger">Error al cargar datos</td></tr>'
            );
        }
    });
}

function escapeHtml(texto) {
    if (!texto) return "";
    return texto.replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
}

// ===============================
// 🔵 ABRIR MODAL EDITAR (CORREGIDO)
// ===============================
function editarOT(id) {

    if (!id || isNaN(id)) {
        alert("ID inválido");
        return;
    }

    $("#loaderEditarOT").show();
    $("#formEditarOT").hide();

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/VerController.php",
        type: "GET",
        data: { id: id },
        dataType: "json",

        success: function (res) {

            if (!res || res.estado !== "ok") {
                alert("No se pudo cargar la información de la OT.");
                return;
            }

            var d = res.data;

            $("#editar_id_ot").val(d.id);
            $("#editar_numero_ot").val(d.numero_ot);
            $("#editar_fecha").val(d.fecha);

            $("#editar_cliente_id").val(d.cliente_id);
            $("#editar_empresa_id").val(d.empresa_id);
            $("#editar_tipo_ot_id").val(d.tipo_ot_id);

            $("#editar_oc_cliente").val(d.oc_cliente);
            $("#editar_descripcion").val(d.descripcion);

            $("#loaderEditarOT").hide();
            $("#formEditarOT").show();

            var modal = new bootstrap.Modal(document.getElementById("modalEditarOT"));
            modal.show();
        },

        error: function () {
            alert("Error al obtener datos de la OT.");
        }
    });
}

function anularOT(id) {
    if (!id || isNaN(id)) {
        alert("ID inválido");
        return;
    }
    $("#anular_id_ot").val(id);
    new bootstrap.Modal(document.getElementById("modalAnularOT")).show();
}

function eliminarOT(id) {
    if (!id || isNaN(id)) {
        alert("ID inválido");
        return;
    }
    $("#eliminar_id_ot").val(id);
    new bootstrap.Modal(document.getElementById("modalEliminarOT")).show();
}
