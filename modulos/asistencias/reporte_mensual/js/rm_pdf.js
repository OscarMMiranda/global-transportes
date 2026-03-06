// ============================================================
// archivo: /modulos/asistencias/reporte_mensual/js/rm_pdf.js
// propósito: lógica para descargar el PDF del reporte mensual
// ============================================================

$(document).ready(function () {

    $("#btn_descargar_pdf").on("click", function () {

        const mes = $("#filtro_mes").val();
        const anio = $("#filtro_anio").val();

        console.log("MES:", mes);
        console.log("AÑO:", anio);

        if (!mes || !anio) {
            alert("Seleccione mes y año antes de descargar el PDF.");
            return;
        }

        window.open(
            "/modulos/asistencias/reporte_mensual/pdf/matriz_pdf.php?mes=" + mes + "&anio=" + anio,
            "_blank"
        );
    });

});
