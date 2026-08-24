// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/js/filtros.js
// ======================================================

// -------------------------------
// Cambio de semana
// -------------------------------
$("#filtro_semana").change(function() {

    var semana = $("#filtro_semana").val();

    if (!validarSemana(semana)) {
        mostrarError("Semana inválida. Formato requerido: YYYY-WXX", "#msgFiltro");
        return;
    }

    limpiarMensajes("#msgFiltro");

    // USAR LA CLASE CORRECTA DEL HTML
    var estado = $(".btn-estado.active").data("estado");

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

    var estado = $(".btn-estado.active").data("estado");

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
