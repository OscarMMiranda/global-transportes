// archivo: /modulos/orden_trabajo/js/ajax.js

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
// 🔵 VALIDAR ID
// ===============================
function validarID(id) {
    if (!id || isNaN(id)) {
        mostrarMensaje("danger", "ID inválido");
        return false;
    }
    return true;
}

// ===============================
// 🔵 GUARDAR EDICIÓN DE OT
// ===============================
function guardarEdicionOT() {

    var datos = $("#formEditarOT").serialize();

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/EditarController.php",
        type: "POST",
        data: datos,
        dataType: "json",

        success: function (res) {

            if (!res || !res.estado) {
                mostrarMensaje("danger", "Respuesta inválida del servidor");
                return;
            }

            if (res.estado === "ok") {

                var modal = bootstrap.Modal.getInstance(
                    document.getElementById("modalEditarOT")
                );

                if (modal) modal.hide();

                cargarTablaOT();
                mostrarMensaje("success", "Orden actualizada correctamente");

            } else {
                mostrarMensaje("danger", escapeHtml(res.mensaje));
            }
        },

        error: function (xhr, status, error) {
            console.error("❌ Error AJAX:", status, error);
            mostrarMensaje("danger", "Error al guardar los cambios");
        }
    });
}

// ===============================
// 🔵 ANULAR OT
// ===============================
function confirmarAnulacionOT() {

    var id = $("#anular_id_ot").val();

    if (!validarID(id)) return;

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/AnularController.php",
        type: "POST",
        data: { id: id },
        dataType: "json",

        success: function (res) {

            if (!res || !res.estado) {
                mostrarMensaje("danger", "Respuesta inválida del servidor");
                return;
            }

            if (res.estado === "ok") {

                var modal = bootstrap.Modal.getInstance(
                    document.getElementById("modalAnularOT")
                );

                if (modal) modal.hide();

                cargarTablaOT();
                mostrarMensaje("warning", "Orden anulada");

            } else {
                mostrarMensaje("danger", escapeHtml(res.mensaje));
            }
        },

        error: function (xhr, status, error) {
            console.error("❌ Error AJAX:", status, error);
            mostrarMensaje("danger", "Error al anular la orden");
        }
    });
}

// ===============================
// 🔵 ELIMINAR OT
// ===============================
function confirmarEliminacionOT() {

    var id = $("#eliminar_id_ot").val();

    if (!validarID(id)) return;

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/DeleteController.php",
        type: "POST",
        data: { id: id },
        dataType: "json",

        success: function (res) {

            if (!res || !res.estado) {
                mostrarMensaje("danger", "Respuesta inválida del servidor");
                return;
            }

            if (res.estado === "ok") {

                var modal = bootstrap.Modal.getInstance(
                    document.getElementById("modalEliminarOT")
                );

                if (modal) modal.hide();

                cargarTablaOT();
                mostrarMensaje("danger", "Orden eliminada");

            } else {
                mostrarMensaje("danger", escapeHtml(res.mensaje));
            }
        },

        error: function (xhr, status, error) {
            console.error("❌ Error AJAX:", status, error);
            mostrarMensaje("danger", "Error al eliminar la orden");
        }
    });
}

// ===============================
// 🔵 MENSAJE CORPORATIVO
// ===============================
function mostrarMensaje(tipo, texto) {

    var html =
        '<div class="alert alert-' + tipo + ' alert-dismissible fade show mt-3" role="alert">' +
        escapeHtml(texto) +
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
        '</div>';

    $("#mensajesOT").html(html);

    setTimeout(function () {
        $(".alert").alert("close");
    }, 4000);
}
