// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/js/orden.js
//  RESPONSABILIDAD: Funciones generales del módulo OT
// ======================================================

// -------------------------------
// Recargar listado completo
// -------------------------------
function recargarListado() {
    var estado = $(".tab-item-activo").data("estado");
    cargarListado(estado);
}

// -------------------------------
// Validar semana corporativa YYYY-WXX
// -------------------------------
function validarSemana(valor) {
    var exp = /^[0-9]{4}-W[0-9]{2}$/;
    return exp.test(valor);
}

// -------------------------------
// Mostrar alerta corporativa
// -------------------------------
function alertaCorp(msg) {
    alert(msg); // simple, compatible con PHP 5.6
}

// -------------------------------
// Formatear fecha a DD/MM/YYYY
// -------------------------------
function formatearFecha(fecha) {

    if (!fecha || fecha.length !== 10) {
        return fecha;
    }

    var partes = fecha.split("-");
    if (partes.length !== 3) {
        return fecha;
    }

    return partes[2] + "/" + partes[1] + "/" + partes[0];
}

// -------------------------------
// Limpiar formularios por ID
// -------------------------------
function limpiarFormulario(idForm) {
    if ($("#" + idForm).length) {
        $("#" + idForm)[0].reset();
    }
}

// -------------------------------
// Desactivar todos los botones del módulo
// -------------------------------
function desactivarBotones() {
    $("button").prop("disabled", true);
}

// -------------------------------
// Activar todos los botones del módulo
// -------------------------------
function activarBotones() {
    $("button").prop("disabled", false);
}

// -------------------------------
// Scroll al inicio del módulo
// -------------------------------
function scrollTopModulo() {
    $('html, body').animate({
        scrollTop: $(".contenedor-ordenes").offset().top - 20
    }, 400);
}

// -------------------------------
// Mostrar mensaje temporal
// -------------------------------
function mensajeTemporal(msg, tipo) {

    var cont = $("#msgTemporal");

    if (!cont.length) {
        return;
    }

    var clase = (tipo === "ok") ? "msg-listado-ok" : "msg-listado-error";

    cont.html("<div class='msg-listado " + clase + "'>" + msg + "</div>");

    setTimeout(function() {
        cont.html("");
    }, 3000);
}
