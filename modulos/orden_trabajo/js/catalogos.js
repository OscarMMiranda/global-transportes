// archivo: /modulos/orden_trabajo/js/catalogos.js

function cargarCatalogo(url, params, target, campoId, campoNombre) {

    $.ajax({
        url: url,
        type: "GET",
        data: params,
        dataType: "json",

        success: function (res) {

            let html = '<option value="">Seleccione...</option>';

            if (res && res.data && res.data.length > 0) {
                $.each(res.data, function (i, item) {
                    html += '<option value="' + item[campoId] + '">' + item[campoNombre] + '</option>';
                });
            }

            $(target).html(html);
        },

        error: function () {
            $(target).html('<option value="">Error</option>');
        }
    });
}

// ===============================
// Catálogos
// ===============================
function cargarClientes() {
    cargarCatalogo(
        "controllers/ClienteController.php",
        { ajax: 1 },
        "#editar_cliente_id",
        "id",
        "nombre"
    );
}

function cargarEmpresas() {
    cargarCatalogo(
        "controllers/CatalogosController.php",
        { tipo: "empresa" },
        "#editar_empresa_id",
        "id",
        "razon_social"
    );
}

function cargarTiposOT() {
    cargarCatalogo(
        "controllers/CatalogosController.php",
        { tipo: "tipo_ot" },
        "#editar_tipo_ot_id",
        "id",
        "nombre"
    );
}

$(document).ready(function () {
    cargarClientes();
    cargarEmpresas();
    cargarTiposOT();
});
