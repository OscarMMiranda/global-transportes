// archivo: /modulos/clientes/js/clientes.js
// Orquestador del módulo Clientes

let tablaClientes;

// ============================
// CARGAR VISTA SEGÚN TAB
// ============================
function cargarVista(tab) {

    let url = "";

    if (tab === "activos") {
        url = "/modulos/clientes/vistas/lista_clientes_activos.php";
    } else if (tab === "inactivos") {
        url = "/modulos/clientes/vistas/lista_clientes_inactivos.php";
    } else {
        return;
    }

    $("#contenedor_tabs").load(url, function (response, status) {

        if (status !== "success") return;

        if (!$("#tablaClientes").length) return;

        inicializarDataTable(tab);
    });
}

// ============================
// INICIALIZAR DATATABLE
// ============================
function inicializarDataTable(tab) {

    let ajaxURL =
        tab === "activos"
            ? "/modulos/clientes/ajax/listar_clientes_activos.php"
            : "/modulos/clientes/ajax/listar_clientes_inactivos.php";

    tablaClientes = $("#tablaClientes").DataTable({
        ajax: {
            url: ajaxURL,
            type: "GET",
            dataSrc: "data"
        },
        columns: [
            { data: "nombre" },
            { data: "tipo_cliente" },
            { data: "ruc" },
            { data: "direccion" },
            { data: "telefono" },
            { data: "correo" },
            // { data: "estado" },
            { data: "acciones" }
        ]
    });
}

// ============================
// EVENTOS DE TABS
// ============================
$(document).on("click", "#tabsClientes .nav-link", function (e) {
    e.preventDefault();

    $("#tabsClientes .nav-link").removeClass("active");
    $(this).addClass("active");

    const tab = $(this).data("tab");
    cargarVista(tab);
});

// ============================
// BOTÓN NUEVO CLIENTE
// ============================
$(document).on("click", "#btnNuevoCliente", function () {
    if (typeof abrirModalNuevoCliente === "function") {
        abrirModalNuevoCliente();
    }
});

// ============================
// INICIO AUTOMÁTICO
// ============================
$(document).ready(function () {
    if ($("#contenedor_tabs").length) {
        cargarVista("activos");
    }
});
