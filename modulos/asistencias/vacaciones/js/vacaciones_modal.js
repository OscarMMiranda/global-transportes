// ============================================================
// JS GLOBAL DEL MODAL DE SOLICITAR VACACIONES
// ============================================================

$(document).ready(function () {

    // Cuando se abre el modal → cargar empresas
    $("#modalSolicitarVacaciones").on("shown.bs.modal", function () {
        cargarEmpresasVac();
    });

    // Cuando cambia empresa → recargar conductores
    $("#vacEmpresa").on("change", function () {
        cargarConductoresVac();
    });

    // Calcular días automáticamente
    $("#vacFechaInicio, #vacFechaFin").on("change", function () {
        calcularDiasVac();
    });

    // Guardar solicitud
    $("#btnGuardarSolicitudVacaciones").on("click", function () {
        guardarSolicitudVac();
    });
});


// ============================================================
// CARGAR EMPRESAS
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

            cargarConductoresVac();
        }
    });
}


// ============================================================
// CARGAR CONDUCTORES
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


// ============================================================
// CALCULAR DÍAS
// ============================================================
function calcularDiasVac() {

    let inicio = $("#vacFechaInicio").val();
    let fin    = $("#vacFechaFin").val();

    if (!inicio || !fin) {
        $("#vacDias").val("");
        return;
    }

    let f1 = new Date(inicio);
    let f2 = new Date(fin);

    if (f2 < f1) {
        $("#vacAlert").removeClass("d-none").text("La fecha fin no puede ser menor que la fecha inicio.");
        $("#vacDias").val("");
        return;
    }

    $("#vacAlert").addClass("d-none");

    let diff = (f2 - f1) / (1000 * 60 * 60 * 24) + 1;

    $("#vacDias").val(diff);
}


// ============================================================
// GUARDAR SOLICITUD
// ============================================================
function guardarSolicitudVac() {

    let empresa    = $("#vacEmpresa").val();
    let conductor  = $("#vacConductor").val();
    let inicio     = $("#vacFechaInicio").val();
    let fin        = $("#vacFechaFin").val();
    let dias       = $("#vacDias").val();
    let tipo       = $("#vacTipo").val();
    let comentario = $("#vacComentario").val();

    if (!empresa || !conductor || !inicio || !fin || !dias) {
        $("#vacAlert").removeClass("d-none").text("Complete todos los campos obligatorios.");
        return;
    }

    $("#vacAlert").addClass("d-none");

    $.ajax({
        url: "../vacaciones/ajax/ajax_solicitudes_guardar.php",
        type: "POST",
        data: {
            empresa: empresa,
            conductor: conductor,
            inicio: inicio,
            fin: fin,
            dias: dias,
            tipo: tipo,
            comentario: comentario
        },
        dataType: "json",

        success: function (response) {

            if (response.success) {
                $("#modalSolicitarVacaciones").modal("hide");
                alert("Solicitud registrada correctamente.");
            } else {
                $("#vacAlert").removeClass("d-none").text(response.message);
            }
        }
    });
}
