// archivo: /modulos/documentos_empresas/js/documentos_historial.js

// ============================================================
// CARGAR HISTORIAL DE UN DOCUMENTO
// ============================================================
function cargarHistorial(documento_id) {

    if (!documento_id) {
        console.error("cargarHistorial(): documento_id no recibido");
        return;
    }

    // Limpia tabla antes de cargar
    $("#tablaHistorial tbody").html(`
        <tr>
            <td colspan="7" class="text-center">Cargando historial...</td>
        </tr>
    `);

    $.ajax({
        url: "/modulos/documentos_empresas/acciones/listar_historial_documento.php",
        type: "GET",
        data: { documento_id: documento_id },
        dataType: "json",

        success: function (resp) {

            if (!resp || !resp.data) {
                console.error("Respuesta inválida en historial:", resp);
                return;
            }

            let html = "";

            resp.data.forEach(function (item) {

                html += `
                    <tr>
                        <td>${item.version}</td>
                        <td>${item.archivo}</td>
                        <td>${item.fecha_inicio}</td>
                        <td>${item.fecha_vencimiento}</td>
                        <td>${item.subido_por}</td>
                        <td>${item.fecha_subida}</td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-preview" 
                                    data-url="${item.url}">
                                Ver
                            </button>
                        </td>
                    </tr>
                `;
            });

            $("#tablaHistorial tbody").html(html);

            $("#modalHistorial").modal("show");
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX historial:", error);
            $("#tablaHistorial tbody").html(`
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        Error al cargar historial
                    </td>
                </tr>
            `);
        }
    });
}
