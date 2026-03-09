// archivo: /modulos/asistencias/vacaciones/js/vacaciones_saldos.js
// ============================================================
// JS: SALDOS DE VACACIONES
// ============================================================

$(document).ready(function () {

    console.log("vacaciones_saldos.js cargado correctamente");

    cargarEmpresas();
    cargarConductores();
    cargarAnios();

    // Cargar saldos al iniciar
    cargarSaldos();

    // Cuando cambia empresa → recargar conductores
    $("#filtroEmpresa").on("change", function () {
        cargarConductores();
    });

    // Aplicar filtros
    $("#btnAplicarFiltros").on("click", function () {
        cargarSaldos();
    });

});


// ============================================================
// FUNCIÓN PRINCIPAL: CARGAR SALDOS
// ============================================================
function cargarSaldos() {

    let empresa   = $("#filtroEmpresa").val();
    let conductor = $("#filtroConductor").val();
    let anio      = $("#filtroAnio").val();

    $("#loaderSaldos").show();

    $.ajax({
        url: "../vacaciones/ajax/ajax_saldos.php",
        type: "POST",
        data: {
            empresa: empresa,
            conductor: conductor,
            anio: anio
        },
        dataType: "json",

        success: function (response) {

            let tbody = $("#tablaSaldosVacaciones tbody");
            tbody.empty();

            if (!response || response.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            No se encontraron resultados
                        </td>
                    </tr>
                `);
                return;
            }

            response.forEach(function (item) {

                let estadoClass = obtenerClaseEstado(item.estado);

                tbody.append(`
                    <tr>
                        <td>${item.conductor}</td>
                        <td>${item.empresa}</td>
                        <td>${item.periodo}</td>
                        <td>${item.dias_ganados}</td>
                        <td>${item.dias_usados}</td>
                        <td>${item.dias_vendidos}</td>
                        <td>${item.dias_pendientes}</td>
                        <td class="${estadoClass}">${item.estado}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" onclick="verDetallePeriodo(${item.id})">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Error al cargar los saldos.");
        },

        complete: function () {
            $("#loaderSaldos").hide();
        }
    });
}


// ============================================================
// FUNCIÓN: OBTENER CLASE CSS SEGÚN ESTADO
// ============================================================
function obtenerClaseEstado(estado) {

    estado = estado.toLowerCase();

    if (estado.includes("abierto")) return "estado-abierto";
    if (estado.includes("cerrado")) return "estado-cerrado";
    if (estado.includes("parcial")) return "estado-parcial";

    return "";
}


// ============================================================
// FUNCIÓN: VER DETALLE DEL PERIODO (abre modal)
// ============================================================
function verDetallePeriodo(idPeriodo) {

    $("#detallePeriodoID").val(idPeriodo);
    $("#modalDetallePeriodo").modal("show");

    cargarDetallePeriodo(idPeriodo);
}


// ============================================================
// CARGAR EMPRESAS
// ============================================================
function cargarEmpresas() {
    $.ajax({
        url: "../vacaciones/ajax/ajax_empresas.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
            let select = $("#filtroEmpresa");
            select.empty();
            select.append(`<option value="">Todas</option>`);

            response.forEach(function (item) {
                select.append(`<option value="${item.id}">${item.razon_social}</option>`);
            });
        }
    });
}


// ============================================================
// CARGAR CONDUCTORES (filtrados por empresa)
// ============================================================
function cargarConductores() {

    let empresa = $("#filtroEmpresa").val();

    $.ajax({
        url: "../vacaciones/ajax/ajax_conductores.php",
        type: "POST",
        data: { empresa: empresa },
        dataType: "json",
        success: function (response) {
            let select = $("#filtroConductor");
            select.empty();
            select.append(`<option value="">Todos</option>`);

            response.forEach(function (item) {
                select.append(`<option value="${item.id}">${item.nombre}</option>`);
            });
        }
    });
}


// ============================================================
// CARGAR AÑOS
// ============================================================
function cargarAnios() {
    $.ajax({
        url: "../vacaciones/ajax/ajax_anios.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
            let select = $("#filtroAnio");
            select.empty();
            select.append(`<option value="">Todos</option>`);

            response.forEach(function (item) {
                select.append(`<option value="${item.anio}">${item.anio}</option>`);
            });
        }
    });
}

// ============================================================
// CARGAR EMPRESAS EN EL MODAL
// ============================================================
function cargarEmpresasVac() {
    $.ajax({
        url: "../vacaciones/ajax/ajax_empresas.php",
        type: "POST",
        dataType: "json",
        success: function (response) {
            let select = $("#vacEmpresa");
            select.empty();
            select.append(`<option value="">Seleccione</option>`);

            response.forEach(function (item) {
                select.append(`<option value="${item.id}">${item.razon_social}</option>`);
            });
        }
    });
}


// ============================================================
// CARGAR CONDUCTORES EN EL MODAL (filtrados por empresa)
// ============================================================
function cargarConductoresVac() {

    let empresa = $("#vacEmpresa").val();

    $.ajax({
        url: "../vacaciones/ajax/ajax_conductores.php",
        type: "POST",
        data: { empresa: empresa },
        dataType: "json",
        success: function (response) {
            let select = $("#vacConductor");
            select.empty();
            select.append(`<option value="">Seleccione</option>`);

            response.forEach(function (item) {
                select.append(`<option value="${item.id}">${item.nombre}</option>`);
            });
        }
    });
}
