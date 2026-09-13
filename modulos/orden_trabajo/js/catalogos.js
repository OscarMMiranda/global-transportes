// archivo: /modulos/orden_trabajo/js/catalogos.js

function cargarCatalogo(url, params, target, campoId, campoNombre) {

    $.ajax({
        url: url,
        type: "GET",
        data: params,
        dataType: "json",

        success: function (res) {

            let html = '<option value="">Seleccione...</option>';

            if (res && res.length > 0) {
                $.each(res, function (i, item) {
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
// CLIENTES
// ===============================
function cargarClientes() {

    // EDITAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/ClienteController.php",
        { ajax: 1 },
        "#editar_cliente_id",
        "id",
        "nombre"
    );

    // CREAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/ClienteController.php",
        { ajax: 1 },
        "#crear_cliente_id",
        "id",
        "nombre"
    );
}

// ===============================
// EMPRESAS
// ===============================
function cargarEmpresas() {

    // EDITAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EmpresaListarController.php",
        {},
        "#editar_empresa_id",
        "id",
        "nombre"
    );

    // CREAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EmpresaListarController.php",
        {},
        "#crear_empresa_id",
        "id",
        "nombre"
    );
}

// ===============================
// TIPOS DE OT
// ===============================
function cargarTiposOT() {

    // EDITAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/TipoOTListarController.php",
        {},
        "#editar_tipo_ot",
        "id",
        "nombre"
    );

    // CREAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/TipoOTListarController.php",
        {},
        "#crear_tipo_ot",
        "id",
        "nombre"
    );
}

// ===============================
// ESTADOS
// ===============================
function cargarEstados() {

    // EDITAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EstadoListarController.php",
        {},
        "#editar_estado_id",
        "id",
        "nombre"
    );

    // CREAR
    cargarCatalogo(
        "/modulos/orden_trabajo/controllers/EstadoListarController.php",
        {},
        "#crear_estado_id",
        "id",
        "nombre"
    );
}

// ===============================
// INICIALIZAR
// ===============================
$(document).ready(function () {
    cargarClientes();
    cargarEmpresas();
    cargarTiposOT();
    cargarEstados();
});
