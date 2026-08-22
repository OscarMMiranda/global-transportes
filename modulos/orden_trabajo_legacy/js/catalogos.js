// archivo: /modulos/orden_trabajo/js/catalogos.js

$(document).ready(function () {
    cargarClientes();
    cargarEmpresas();
    cargarTiposOT();
});

// ============================================================
// 🔵 Cargar Clientes (para el modal EDITAR)
// ============================================================
function cargarClientes() {

    $.ajax({
        url: "/modulos/orden_trabajo/controllers/ClienteController.php",
        type: "GET",
        data: { ajax: 1 },
        dataType: "json",

        success: function (res) {

            let html = '<option value="">Seleccione...</option>';

            if (res && res.estado === "ok") {
                $.each(res.data, function (i, item) {
                    html += '<option value="' + item.id + '">' + item.nombre + '</option>';
                });
            }

            $("#editar_cliente_id").html(html);
        },

        error: function () {
            $("#editar_cliente_id").html('<option value="">Error</option>');
        }
    });
}

// ============================================================
// 🔵 Cargar Empresas (para el modal EDITAR)
// ============================================================
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

            $("#editar_empresa_id").html(html);
        },

        error: function () {
            $("#editar_empresa_id").html('<option value="">Error</option>');
        }
    });
}

// ============================================================
// 🔵 Cargar Tipos de OT (para el modal EDITAR)
// ============================================================
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

            $("#editar_tipo_ot_id").html(html);
        },

        error: function () {
            $("#editar_tipo_ot_id").html('<option value="">Error</option>');
        }
    });
}
