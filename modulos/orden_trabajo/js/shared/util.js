// ======================================================
//  JS: util.js
//  RESPONSABILIDAD: Funciones utilitarias corporativas OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.5)
// ======================================================


// ======================================================
//  MENSAJES CORPORATIVOS
// ======================================================
function mostrarOk(msg, contenedor) {
    $(contenedor).html(
        '<div class="msg-listado msg-listado-ok">' + (msg || '') + '</div>'
    );
}

function mostrarError(msg, contenedor) {
    $(contenedor).html(
        '<div class="msg-listado msg-listado-error">' + (msg || '') + '</div>'
    );
}

function limpiarMensajes(contenedor) {
    $(contenedor).empty();
}


// ======================================================
//  LOADER CORPORATIVO
// ======================================================
function loaderOn(id) {
    $("#" + id).removeClass("d-none").show();
}

function loaderOff(id) {
    $("#" + id).addClass("d-none").hide();
}


// ======================================================
//  BOTONES CORPORATIVOS
// ======================================================
function deshabilitarBoton(id) {
    $("#" + id).prop("disabled", true);
}

function habilitarBoton(id) {
    $("#" + id).prop("disabled", false);
}


// ======================================================
//  VALIDACIONES CORPORATIVAS
// ======================================================
function campoVacio(valor) {
    return (valor === null || valor === undefined || $.trim(valor) === "");
}

function campoNoValido(valor) {
    return (valor === null || valor === undefined || valor === "" || valor === "0");
}


// ======================================================
//  FORMATEO DE TEXTO
// ======================================================
function toUpper(id) {
    var v = $("#" + id).val() || "";
    $("#" + id).val(v.toUpperCase());
}

function toLower(id) {
    var v = $("#" + id).val() || "";
    $("#" + id).val(v.toLowerCase());
}


// ======================================================
//  SCROLL CORPORATIVO
// ======================================================
function scrollToElemento(id) {
    var el = $("#" + id);
    if (el.length === 0) return;

    $('html, body').animate({
        scrollTop: el.offset().top - 20
    }, 400);
}


// ======================================================
//  SELECT CORPORATIVO (versión robusta)
// ======================================================
function cargarSelect(selector, lista, seleccionado) {

    // Seguridad corporativa
    if (!Array.isArray(lista) || lista.length === 0) {
        $(selector).html("<option value=''>Seleccione...</option>");
        return;
    }

    var html = "<option value=''>Seleccione...</option>";

    lista.forEach(function (item) {

        var id = item.id || "";
        var nombre = item.nombre || "";
        var sel = (id == seleccionado) ? "selected" : "";

        html += '<option value="' + id + '" ' + sel + '>' + nombre + '</option>';
    });

    $(selector).html(html);
}
