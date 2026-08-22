// archivo: /modulos/orden_trabajo/js/ajax.js

function escapeHtml(texto) {
    if (!texto) return "";
    return texto
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

function cargarTablaOT() {

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/ListController.php",
        type: "POST",
        data: {
            semana: $("#filtroSemana").val(),
            ajax: 1,
            estado: "ALL"
        },
        dataType: "json",

        success: function (res) {

            if (!res || res.estado !== "ok") {
                $("#tbodyActivas").html(
                    '<tr><td colspan="8" class="text-center text-danger">Error al cargar datos</td></tr>'
                );
                return;
            }

            $("#tbodyActivas").html(res.html_activas);
            $("#tbodyAnuladas").html(res.html_anuladas);
            $("#tbodyEliminadas").html(res.html_eliminadas);

            if (typeof inicializarTabla === "function") {
                inicializarTabla("#tablaOrdenesActivas");
                inicializarTabla("#tablaOrdenesAnuladas");
                inicializarTabla("#tablaOrdenesEliminadas");
            }
        },

        error: function (xhr, status, error) {
            console.error("❌ Error AJAX:", status, error);

            $("#tbodyActivas").html(
                '<tr><td colspan="8" class="text-center text-danger">Error al cargar datos</td></tr>'
            );
        }
    });
}
