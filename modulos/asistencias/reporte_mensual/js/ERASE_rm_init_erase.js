/*
    archivo: /modulos/asistencias/reporte_mensual/js/rm_init.js
    propósito: controlador principal del Reporte Mensual
*/

// ============================================================
//  CONTROLADOR PRINCIPAL DEL REPORTE MENSUAL
// ============================================================

$(document).ready(function () {

    console.log("Reporte Mensual inicializado");

    // Cargar conductores al inicio
    rm_cargar_conductores();

    // Evento principal
    $("#btnBuscar").on("click", function () {
        rm_cargar_reporte();
    });

});


// ============================================================
//  FUNCIÓN PRINCIPAL: CARGAR REPORTE
// ============================================================
function rm_cargar_reporte() {

    let empresa   = $("#filtro_empresa").val();
    let conductor = $("#filtro_conductor").val();
    let mes       = $("#filtro_mes").val();
    let anio      = $("#filtro_anio").val();
    let vista     = $("#filtro_vista").val();

    if (!empresa || !conductor || !mes || !anio) {
        rm_toast("error", "Debe completar todos los filtros");
        return;
    }

    $.ajax({
        url: "acciones/obtener_reporte_mensual.php",
        type: "POST",
        data: {
            empresa_id: empresa,
            conductor_id: conductor,
            mes: mes,
            anio: anio,
            vista: vista
        },
        dataType: "json",
        beforeSend: function () {

            // Solo limpiar tabla si la vista es TABLA
            if (vista !== "matriz") {
                $("#tabla_reporte tbody").html(`
                    <tr><td colspan="10" class="text-center">Cargando...</td></tr>
                `);
            }
        },

        success: function (resp) {

            if (!resp.ok) {
                rm_toast("error", resp.msg || "Error desconocido");
                return;
            }

            // ============================
            // CAMBIO REAL DE VISTA
            // ============================
            if (vista === "matriz") {

                // Ocultar tabla
                $("#vista_tabla").hide();

                // Mostrar matriz
                $("#vista_matriz").show();

                // Renderizar matriz
                rm_render_vista_matriz(resp.data, mes, anio);

            } else {

                // Mostrar tabla
                $("#vista_tabla").show();

                // Ocultar matriz
                $("#vista_matriz").hide();

                // Renderizar tabla normal
                rm_render_tabla(resp.data);
            }

            // Totales (siempre)
            rm_totales_render(resp.totales);

            rm_toast("success", "Reporte generado correctamente");
        },

        error: function (xhr) {
            console.log(xhr.responseText);
            rm_toast("error", "Error de comunicación con el servidor");
        }
    });
}
