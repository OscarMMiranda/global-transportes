    //  archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla_totals.js
    // Función para calcular y mostrar los totales de asistencias, faltas, horas trabajadas y horas extra en el reporte mensual de asistencias

function rm_calcular_totales(data) {
    let totalAsistencias = 0;
    let totalFaltas = 0;
    let totalHoras = 0;
    let totalHorasExtra = 0;

    data.forEach(item => {
        if (item.estado === 'ASISTENCIA') totalAsistencias++;
        if (item.estado === 'FALTA') totalFaltas++;

        totalHoras += parseFloat(item.horas_trabajadas || 0);
        totalHorasExtra += parseFloat(item.horas_extra || 0);
    });

    $('#total_asistencias').val(totalAsistencias);
    $('#total_faltas').val(totalFaltas);
    $('#total_horas').val(totalHoras.toFixed(2));
    $('#total_horas_extra').val(totalHorasExtra.toFixed(2));
}
