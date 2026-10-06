// ======================================================
//  JS: ot_componentes.js
//  MÓDULO: Órdenes de Trabajo (OT)
//  RESPONSABILIDAD: Componentes visuales y utilitarios OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.8)
// ======================================================

console.log("[OT] Componentes cargados correctamente");

// ======================================================
//  INICIALIZACIÓN GENERAL
// ======================================================

$(document).ready(function () {

    console.log("[OT] Inicializando componentes");

    // --------------------------------------------------
    // FILTRO DE SEMANA
    // --------------------------------------------------
    $(document)
        .off('change.otSemana', '#filtro_semana')
        .on('change.otSemana', '#filtro_semana', function () {

            var semana = ($(this).val() || '').toString().trim();

            console.log("[OT] Semana seleccionada:", semana);

            if (typeof tablaOT !== 'undefined' &&
                tablaOT !== null) {

                tablaOT.ajax.reload(null, false);

            } else {

                console.warn("[OT] tablaOT no inicializada");

            }
        });
});

// ======================================================
//  1. FORMATEO CORPORATIVO
// ======================================================

function otUpper(selector)
{
    var el = $(selector);

    if (!el.length) {
        return;
    }

    el.val(el.val().toUpperCase());
}

function otLower(selector)
{
    var el = $(selector);

    if (!el.length) {
        return;
    }

    el.val(el.val().toLowerCase());
}

function otTrim(selector)
{
    var el = $(selector);

    if (!el.length) {
        return;
    }

    el.val($.trim(el.val()));
}

// ======================================================
//  2. SELECT2
// ======================================================

function otSelect2(selector)
{
    var el = $(selector);

    if (!el.length) {
        return;
    }

    el.select2({
        theme: "bootstrap-5",
        width: "100%",
        placeholder: "Seleccione...",
        allowClear: true
    });
}

// ======================================================
//  3. BOTONES
// ======================================================

function otBtnDisable(id)
{
    $("#" + id).prop("disabled", true);
}

function otBtnEnable(id)
{
    $("#" + id).prop("disabled", false);
}

// ======================================================
//  4. LOADER
// ======================================================

function otLoaderOn(id)
{
    $("#" + id)
        .removeClass("d-none")
        .show();
}

function otLoaderOff(id)
{
    $("#" + id)
        .addClass("d-none")
        .hide();
}

// ======================================================
//  5. MENSAJES
// ======================================================

function otMsgOk(msg, contenedor)
{
    $(contenedor).html(
        '<div class="msg-listado msg-listado-ok">' +
        (msg || '') +
        '</div>'
    );
}

function otMsgError(msg, contenedor)
{
    $(contenedor).html(
        '<div class="msg-listado msg-listado-error">' +
        (msg || '') +
        '</div>'
    );
}

function otMsgClear(contenedor)
{
    $(contenedor).empty();
}

// ======================================================
//  6. SCROLL
// ======================================================

function otScrollTo(id)
{
    var el = $("#" + id);

    if (!el.length) {
        return;
    }

    $('html, body').animate({
        scrollTop: el.offset().top - 20
    }, 400);
}

// ======================================================
//  7. VALIDACIONES
// ======================================================

function otCampoVacio(valor)
{
    return (
        valor === null ||
        valor === undefined ||
        $.trim(valor) === ""
    );
}

function otCampoNoValido(valor)
{
    return (
        valor === null ||
        valor === undefined ||
        valor === "" ||
        valor === "0"
    );
}

// ======================================================
//  8. CARGA DE SELECT
// ======================================================

function otCargarSelect(selector, lista, seleccionado)
{
    if (!Array.isArray(lista)) {

        $(selector).html(
            "<option value=''>Seleccione...</option>"
        );

        return;
    }

    var html = "<option value=''>Seleccione...</option>";

    lista.forEach(function (item) {

        var id = item.id || "";
        var nombre = item.nombre || "";
        var sel = (id == seleccionado)
            ? "selected"
            : "";

        html +=
            '<option value="' +
            id +
            '" ' +
            sel +
            '>' +
            nombre +
            '</option>';
    });

    $(selector).html(html);
}

// ======================================================
//  9. ALERTAS
// ======================================================

function otAlert(msg, tipo)
{
    tipo = tipo || "info";

    var colores = {
        info: "alert-primary",
        ok: "alert-success",
        error: "alert-danger",
        warn: "alert-warning"
    };

    var clase = colores[tipo] || colores.info;

    return (
        '<div class="alert ' +
        clase +
        ' py-2 px-3 mb-2">' +
        msg +
        '</div>'
    );
}