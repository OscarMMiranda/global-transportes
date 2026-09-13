// Archivo : /modulos/orden_trabajo/js/dinamicos.js


// ============================================================
// MOSTRAR / OCULTAR CAMPOS SEGÚN TIPO DE OT
// ============================================================
function mostrarCamposEditar(tipo) {

    $("#campo_importacion").hide();
    $("#campo_exportacion").hide();
    $("#campo_nacional").hide();

    if (tipo === "IMPORTACION" || tipo === "IMPORTACIÓN") {
        $("#campo_importacion").show();
    }

    if (tipo === "EXPORTACION" || tipo === "EXPORTACIÓN") {
        $("#campo_exportacion").show();
    }

    if (tipo === "NACIONAL") {
        $("#campo_nacional").show();
    }
}
