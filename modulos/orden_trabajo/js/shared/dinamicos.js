// ============================================================
// 1. MOSTRAR / OCULTAR CAMPOS SEGÚN TIPO DE OT
// ============================================================
// ARCHIVO: /modulos/orden_trabajo/js/dinamicos.js
// MOSTRAR / OCULTAR CAMPOS SEGÚN TIPO DE OT
// GLOBAL 2026 — Arquitectura Limpia
// ============================================================
function mostrarCamposEditar(tipo) {

    // Normalización corporativa
    const t = (tipo || "").toUpperCase().trim();

    // Ocultar todo primero
    $("#campo_importacion, #campo_exportacion, #campo_nacional").hide();

    // Mostrar según tipo
    if (t === "IMPORTACION" || t === "IMPORTACIÓN") {
        $("#campo_importacion").show();
        return;
    }

    if (t === "EXPORTACION" || t === "EXPORTACIÓN") {
        $("#campo_exportacion").show();
        return;
    }

    if (t === "NACIONAL") {
        $("#campo_nacional").show();
        return;
    }
}
