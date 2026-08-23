// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/js/filtros.js
//  RESPONSABILIDAD: Control de filtros del módulo OT
// ======================================================

// -------------------------------
// Cambio de semana
// -------------------------------
$("#filtro_semana").change(function() {

    var semana = $("#filtro_semana").val();

    // Validación corporativa YYYY-WXX
    if (!validarSemana(semana)) {
        mostrarError("Semana inválida. Formato requerido: YYYY-WXX", "#msgFiltro");
        return;
    }

    limpiarMensajes("#msgFiltro");

    // Recargar listado según pestaña activa
    var estado = $(".tab-item-activo").data("estado");
    cargarListado(estado);
});

// -------------------------------
// Botón de recarga manual
// -------------------------------
$("#btnRecargar").click(function() {

    var semana = $("#filtro_semana").val();

    if (!validarSemana(semana)) {
        mostrarError("Semana inválida. Formato requerido: YYYY-WXX", "#msgFiltro");
        return;
    }

    limpiarMensajes("#msgFiltro");

    var estado = $(".tab-item-activo").data("estado");
    cargarListado(estado);
});

// -------------------------------
// Establecer semana actual (opcional)
// -------------------------------
function setSemanaActual(valor) {
    $("#filtro_semana").val(valor);
}

// -------------------------------
// Obtener semana seleccionada
// -------------------------------
function getSemana() {
    return $("#filtro_semana").val();
}
