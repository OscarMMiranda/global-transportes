// archivo: /modulos/asistencias/reporte_mensual/js/tabla/rm_export_excel.js    
// ============================================================
//  MÓDULO: rm_export_excel.js
//  RESPONSABILIDAD: Enviar filtros al backend para exportar Excel
// ============================================================

$(document).ready(function () {

    $("#btnExportarExcel").on("click", function () {

        let empresa   = $("#f_empresa").val();
        let conductor = $("#f_conductor").val();
        let mes       = $("#f_mes").val();
        let anio      = $("#f_anio").val();

        let url = "acciones/exportar_excel.php"
                + "?empresa="   + encodeURIComponent(empresa)
                + "&conductor=" + encodeURIComponent(conductor)
                + "&mes="       + encodeURIComponent(mes)
                + "&anio="      + encodeURIComponent(anio);

        window.open(url, "_blank");
    });

});
