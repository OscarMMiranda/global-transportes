//archivo: modulos/asistencias/reporte_mensual/js/reporte_mensual.js
// Este archivo se encarga de manejar la lógica principal del reporte mensual de asistencias, incluyendo la carga de datos y la interacción con el usuario


$(document).ready(function () {

    // ============================
    //  EVENTO PRINCIPAL: BOTÓN BUSCAR
    // ============================
    $("#btnBuscar").on("click", function () {
        cargarReporteMensual();
    });

    // ============================
    //  FUNCIÓN PRINCIPAL
    // ============================
    function cargarReporteMensual() {

        let empresa   = $("#filtro_empresa").val();
        let conductor = $("#filtro_conductor").val();
        let mes       = $("#filtro_mes").val();
        let anio      = $("#filtro_anio").val();
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
                $("#tabla_reporte tbody").html(
                    `<tr><td colspan="10" class="text-center">Cargando...</td></tr>`
                );
            },
            success: function (resp) {

                if (!resp.ok) {
                    mostrarToast("error", resp.msg || "Error desconocido");
                    return;
                }

                // ============================
                //  STEP 9: RESPUESTA FINAL
                // ============================
                if (resp.step === 9) {
                    renderizarTabla(resp.data);
                    mostrarToast("success", "Reporte generado correctamente");
                }
            },
            error: function (xhr) {
                mostrarToast("error", "Error de comunicación con el servidor");
                console.log(xhr.responseText);
            }
        });
    }

    // ============================
    //  RENDERIZAR TABLA
    // ============================
    function renderizarTabla(data) {

        let html = "";

        if (data.length === 0) {
            html = `<tr><td colspan="10" class="text-center">No hay registros</td></tr>`;
            $("#tabla_reporte tbody").html(html);
            return;
        }

        data.forEach(function (r) {
            html += `
                <tr>
                    <td>${r.fecha}</td>
                    <td>${r.conductor}</td>
                    <td>${r.empresa_id}</td>
                    <td>${r.tipo || "-"}</td>

                    <td>${r.hora_entrada || "-"}</td>
                    <td>${r.hora_salida || "-"}</td>
                    <td>${r.observacion || "-"}</td>
                    <td>${r.es_feriado == 1 ? "Sí" : "No"}</td>
                </tr>
            `;
        });

        $("#tabla_reporte tbody").html(html);
    }

    // ============================
    //  TOAST CORPORATIVO
    // ============================
    function mostrarToast(tipo, mensaje) {

        let clase = (tipo === "success") ? "toast-success" : "toast-error";

        $("#toast")
            .removeClass()
            .addClass(clase)
            .text(mensaje)
            .fadeIn(200);

        setTimeout(() => {
            $("#toast").fadeOut(300);
        }, 2500);
    }

});
