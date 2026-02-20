// archivo: /modulos/asistencias/reporte_mensual/js/rm_controlador.js
// ============================================================
//  MÓDULO: rm_controlador.js
//  RESPONSABILIDAD: Orquestar filtros → AJAX → render → totales
// ============================================================

$(document).ready(function () {

    // Evento principal
    $("#btnBuscar").on("click", function () {
        rm_cargar_reporte();
    });

    // Cargar reporte
    function rm_cargar_reporte() {

        let empresa   = $("#f_empresa").val();
        let conductor = $("#f_conductor").val();
        let mes       = $("#f_mes").val();
        let anio      = $("#f_anio").val();
        let vista     = $("#f_vista").val();

        $.ajax({
            url: "acciones/obtener_reporte_mensual.php",
            type: "POST",
            data: {
                empresa: empresa,
                conductor: conductor,
                mes: mes,
                anio: anio,
                vista: vista
            },
            dataType: "json",
            beforeSend: function () {
                $("#tabla_reporte tbody").html(`
                    <tr><td colspan="10" class="text-center">Cargando...</td></tr>
                `);
            },
            success: function (resp) {

                if (!resp.ok) {
                    rm_toast("error", resp.msg || "Error desconocido");
                    return;
                }

                if (resp.step === 9) {

                    if (vista === "tabla") {
                        rm_render_tabla(resp.data);
                    }

                    if (vista === "mensual") {
                        rm_render_vista_mensual(resp.data, mes, anio);
                    }

                    rm_toast("success", "Reporte generado correctamente");
                }
            },
            error: function (xhr) {
                rm_toast("error", "Error de comunicación con el servidor");
                console.log(xhr.responseText);
            }
        });
    }

});
