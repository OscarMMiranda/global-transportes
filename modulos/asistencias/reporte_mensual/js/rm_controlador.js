// ============================================================
// archivo: /modulos/asistencias/reporte_mensual/js/rm_controlador.js
// propósito: controlador principal del Reporte Mensual
// ============================================================

$(document).ready(function () {

    console.log("CONTROLADOR CARGADO");

    // Evento principal: generar reporte
    $("#btn_buscar").on("click", function () {
        rm_cargar_reporte();
    });

    // ============================================================
    // EXPORTAR EXCEL (EVENTO GLOBAL, NO DENTRO DE rm_cargar_reporte)
    // ============================================================
    $("#btn_exportar_excel").on("click", function () {

        let empresa   = $("#filtro_empresa").val();
        let conductor = $("#filtro_conductor").val();
        let mes       = $("#filtro_mes").val();
        let anio      = $("#filtro_anio").val();

        if (!mes || !anio) {
            rm_toast("error", "Debe seleccionar mes y año");
            return;
        }

        let url = "acciones/exportar_excel.php"
                + "?empresa_id="   + encodeURIComponent(empresa)
                + "&conductor_id=" + encodeURIComponent(conductor)
                + "&mes="          + encodeURIComponent(mes)
                + "&anio="         + encodeURIComponent(anio);

        window.open(url, "_blank");
    });

});


// ============================================================
// FUNCIÓN PRINCIPAL: CARGAR REPORTE
// ============================================================
function rm_cargar_reporte() {

    let empresa   = $("#filtro_empresa").val();
    let conductor = $("#filtro_conductor").val();
    let mes       = $("#filtro_mes").val();
    let anio      = $("#filtro_anio").val();
    let vista     = $("#filtro_vista").val();

    console.log("VISTA SELECCIONADA:", vista);

    if (!mes || !anio) {
        rm_toast("error", "Debe seleccionar mes y año");
        return;
    }

    if (vista === "mensual" && !conductor) {
        rm_toast("error", "Debe seleccionar un conductor para la vista mensual");
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
            if (vista !== "matriz") {
                $("#tabla_reporte tbody").html(`
                    <tr><td colspan="10" class="text-center">Cargando...</td></tr>
                `);
            }
        },
        success: function (resp) {

            window.lastResp = resp;

            if (!resp.ok) {
                rm_toast("error", resp.msg || "Error desconocido");
                return;
            }

            // ============================
            // CAMBIO REAL DE VISTA
            // ============================
            if (vista === "matriz") {

                $("#vista_tabla").hide();
                $("#vista_matriz").show();

                // 🔥 ESTA LÍNEA FALTABA
                $("#leyendas_matriz").show();

                rm_render_vista_matriz(resp.data, mes, anio);

            } else {

                $("#vista_matriz").hide();
                $("#vista_tabla").show();

                // 🔥 ESTA LÍNEA TAMBIÉN FALTABA
                $("#leyendas_matriz").hide();

                if (vista === "tabla") {
                    rm_render_tabla(resp.data);
                }

                if (vista === "mensual") {
                    rm_render_vista_mensual(resp.data, mes, anio);
                }
            }

            rm_totales_render(resp.totales);
            rm_toast("success", "Reporte generado correctamente");
        },
        error: function (xhr) {
            rm_toast("error", "Error de comunicación con el servidor");
            console.log(xhr.responseText);
        }
    });
}
