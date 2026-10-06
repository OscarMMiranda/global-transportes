// ======================================================
//  JS: ot_modals_utils.js
//  RESPONSABILIDAD: Utilidades corporativas para modales OT
//  GLOBAL 2026 — Arquitectura Limpia
// ======================================================


// MENSAJES CORPORATIVOS
function mostrarOk(msg, contenedor) {
    $(contenedor).html('<div class="msg-listado msg-listado-ok">' + (msg || '') + '</div>');
}

function mostrarError(msg, contenedor) {
    $(contenedor).html('<div class="msg-listado msg-listado-error">' + (msg || '') + '</div>');
}

function limpiarMensajes(contenedor) {
    $(contenedor).empty();
}


// LOADER CORPORATIVO
function loaderOn(id) {
    $("#" + id).removeClass("d-none").show();
}

function loaderOff(id) {
    $("#" + id).addClass("d-none").hide();
}


// SELECT CORPORATIVO
function cargarSelect(selector, lista, seleccionado) {

    if (!Array.isArray(lista)) {
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


// SCROLL CORPORATIVO
function scrollToElemento(id) {
    var el = $("#" + id);
    if (el.length === 0) return;

    $('html, body').animate({
        scrollTop: el.offset().top - 20
    }, 400);
}
