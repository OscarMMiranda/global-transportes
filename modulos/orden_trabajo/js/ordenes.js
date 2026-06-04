// archivo: /modulos/orden_trabajo/js/ordenes.js

// ===============================
// 🔵 CARGA INICIAL
// ===============================
$(document).ready(function () {
    cargarTablaOT();
});

// ===============================
// 🔵 FUNCIÓN PRINCIPAL: CARGAR TABLA
// ===============================
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

                    // Sanitizar texto para evitar inyección visual
                    var numero = escapeHtml(ot.numero_ot);
                    var fecha = escapeHtml(ot.fecha);
                    var cliente = escapeHtml(ot.cliente);
                    var tipo = escapeHtml(ot.tipo_ot);
                    var empresa = escapeHtml(ot.empresa);
                    var estado = escapeHtml(ot.estado);

                    html += "<tr>" +
                        "<td>" + numero + "</td>" +
                        "<td>" + fecha + "</td>" +
                        "<td>" + cliente + "</td>" +
                        "<td>" + tipo + "</td>" +
                        "<td>" + empresa + "</td>" +
                        "<td>" + estado + "</td>" +

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
// 🔵 ABRIR MODAL EDITAR
// ===============================
function editarOT(id) {

    if (!id || isNaN(id)) {
        alert("ID inválido");
        return;
    }

    $("#editar_id_ot").val(id);
    $("#loaderEditarOT").show();
    $("#formEditarOT").hide();

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/VerController.php",
        type: "GET",
        data: { id: id },
        dataType: "json",

        success: function (res) {

            if (!res || !res.id) {
                alert("No se pudo cargar la información de la OT.");
                return;
            }

            $("#editar_numero_ot").val(res.numero_ot);
            $("#editar_fecha").val(res.fecha);
            $("#editar_cliente").val(res.cliente);
            $("#editar_empresa").val(res.empresa);
            $("#editar_tipo_ot").val(res.tipo_ot);
            $("#editar_oc_cliente").val(res.oc_cliente);
            $("#editar_descripcion").val(res.descripcion);

            $("#loaderEditarOT").hide();
            $("#formEditarOT").show();
        },

        error: function () {
            alert("Error al obtener datos de la OT.");
        }
    });

    var modal = new bootstrap.Modal(document.getElementById("modalEditarOT"));
    modal.show();
}

// ===============================
// 🔵 ABRIR MODAL ANULAR
// ===============================
function anularOT(id) {

    if (!id || isNaN(id)) {
        alert("ID inválido");
        return;
    }

    $("#anular_id_ot").val(id);

    var modal = new bootstrap.Modal(document.getElementById("modalAnularOT"));
    modal.show();
}

// ===============================
// 🔵 ABRIR MODAL ELIMINAR
// ===============================
function eliminarOT(id) {

    if (!id || isNaN(id)) {
        alert("ID inválido");
        return;
    }

    $("#eliminar_id_ot").val(id);

    var modal = new bootstrap.Modal(document.getElementById("modalEliminarOT"));
    modal.show();
}
