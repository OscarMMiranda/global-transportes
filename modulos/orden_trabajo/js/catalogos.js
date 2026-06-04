// archivo: /modulos/orden_trabajo/js/catalogos.js

$(document).ready(function () {
    cargarTiposOT();
    cargarEmpresas();
});

// ===============================
// 🔵 Cargar Tipos de Orden
// ===============================
function cargarTiposOT() {

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/CatalogosController.php",
        type: "GET",
        data: { tipo: "tipo_ot" },
        dataType: "json",

        success: function (res) {

            let html = '<option value="">Seleccione...</option>';

            if (res && res.length > 0) {
                $.each(res, function (i, item) {
                    html += '<option value="' + item.id + '">' + item.nombre + '</option>';
                });
            }

            $("#tipo_ot_id").html(html);
        },

        error: function (xhr, status, error) {
            console.error("❌ Error cargando Tipo OT:", status, error);
            $("#tipo_ot_id").html('<option value="">Error</option>');
        }
    });
}

// ===============================
// 🔵 Cargar Empresas
// ===============================
function cargarEmpresas() {

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/CatalogosController.php",
        type: "GET",
        data: { tipo: "empresa" },
        dataType: "json",

        success: function (res) {

            let html = '<option value="">Seleccione...</option>';

            if (res && res.length > 0) {
                $.each(res, function (i, item) {
                    html += '<option value="' + item.id + '">' + item.razon_social + '</option>';
                });
            }

            $("#empresa_id").html(html);
        },

        error: function (xhr, status, error) {
            console.error("❌ Error cargando Empresas:", status, error);
            $("#empresa_id").html('<option value="">Error</option>');
        }
    });
}
