// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_totales.js
// Funciones para calcular y mostrar los totales en el reporte mensual de asistencias


function rm_totales_update(asistencias, faltas, horas) {

    $("#total_asistencias").val(asistencias);
    $("#total_faltas").val(faltas);
    $("#total_horas").val(horas.toFixed(2));
    $("#total_horas_extra").val("0.00"); // si luego quieres horas extra, lo agregamos
}

function rm_totales_reset() {
    rm_totales_update(0, 0, 0);
}
