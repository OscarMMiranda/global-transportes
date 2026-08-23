// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/js/util.js
//  RESPONSABILIDAD: Funciones utilitarias del módulo
// ======================================================

// -------------------------------
// Mostrar mensaje OK corporativo
// -------------------------------
function mostrarOk(msg, contenedor) {
    var html = '<div class="msg-listado msg-listado-ok">' + msg + '</div>';
    $(contenedor).html(html);
}

// -------------------------------
// Mostrar mensaje ERROR corporativo
// -------------------------------
function mostrarError(msg, contenedor) {
    var html = '<div class="msg-listado msg-listado-error">' + msg + '</div>';
    $(contenedor).html(html);
}

// -------------------------------
// Limpiar mensajes
// -------------------------------
function limpiarMensajes(contenedor) {
    $(contenedor).html("");
}

// -------------------------------
// Loader corporativo ON
// -------------------------------
function loaderOn(id) {
    $("#" + id).show();
}

// -------------------------------
// Loader corporativo OFF
// -------------------------------
function loaderOff(id) {
    $("#" + id).hide();
}

// -------------------------------
// Deshabilitar botón
// -------------------------------
function deshabilitarBoton(id) {
    $("#" + id).prop("disabled", true);
}

// -------------------------------
// Habilitar botón
// -------------------------------
function habilitarBoton(id) {
    $("#" + id).prop("disabled", false);
}

// -------------------------------
// Validar campos vacíos
// -------------------------------
function campoVacio(valor) {
    return (valor === null || valor === undefined || $.trim(valor) === "");
}

// -------------------------------
// Formatear texto a mayúsculas
// -------------------------------
function toUpper(id) {
    var v = $("#" + id).val();
    $("#" + id).val(v.toUpperCase());
}

// -------------------------------
// Formatear texto a minúsculas
// -------------------------------
function toLower(id) {
    var v = $("#" + id).val();
    $("#" + id).val(v.toLowerCase());
}

// -------------------------------
// Scroll suave a un elemento
// -------------------------------
function scrollToElemento(id) {
    $('html, body').animate({
        scrollTop: $("#" + id).offset().top - 20
    }, 400);
}
