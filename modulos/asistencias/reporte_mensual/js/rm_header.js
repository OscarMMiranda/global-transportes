//    archivo: modulos/asistencias/reporte_mensual/js/rm_header.js
// ============================================================
//  MÓDULO: rm_header.js
//  RESPONSABILIDAD: Actualizar la información del header del reporte mensual
// ============================================================

function rm_header_actualizar_info() {

    $("#rm_info_conductor").text($("#filtro_conductor option:selected").text());
    $("#rm_info_empresa").text($("#filtro_empresa option:selected").text());

    const mes = $("#filtro_mes option:selected").text();
    const anio = $("#filtro_anio").val();
    $("#rm_info_periodo").text(`${mes} ${anio}`);

    const vista = $("#filtro_vista option:selected").text();
    $("#rm_info_vista").text(vista);
}

function rm_header_actualizar_totales(t) {
    $("#rm_total_asistencias").text(t.total_registros || 0);
    $("#rm_total_faltas").text(t.total_faltas || 0);
    $("#rm_total_horas").text(t.total_horas || 0);
    $("#rm_total_horas_extra").text(t.total_horas_extra || 0);
}
