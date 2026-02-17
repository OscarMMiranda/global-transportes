 // archivo: /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla.js

var RM_Tabla = {

    init: function () {},

    cargar: function () {

        var filtros = RM_Filtros.getFiltros();

        $.ajax({
            url: "acciones/obtener_reporte_mensual.php",
            type: "POST",
            data: filtros,
            dataType: "json",
            success: function (resp) {
                RM_Tabla_Renderer.render(resp.data);
            },
            error: function () {
                alert("Error al cargar el reporte mensual.");
            }
        });

    }
};
