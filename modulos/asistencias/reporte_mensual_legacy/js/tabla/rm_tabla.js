 // archivo: /modulos/asistencias/reporte_mensual/js/tabla/rm_tabla.js

var RM_Tabla = {

    cargar: function () {

        var filtros = RM_Filtros.getFiltros();

        $.ajax({
            url: "../../acciones/obtener_reporte_mensual.php",
            type: "POST",
            data: filtros,
            dataType: "json",
            success: function (resp) {
                if (resp.ok) {
                    rm_render_tabla(resp.data);
                } else {
                    alert(resp.msg);
                }
            },
            error: function () {
                alert("Error al cargar el reporte mensual.");
            }
        });

    }
};
