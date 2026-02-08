// archivo: modulos/documentos_empresas/js/documentos_historial.js

// ============================================================
// CARGAR HISTORIAL DE DOCUMENTOS DE EMPRESA
// ============================================================
function cargarHistorial(empresa_id, tipo_documento_id) {

    if (!empresa_id || !tipo_documento_id) {
        console.error("cargarHistorial(): parámetros incompletos", empresa_id, tipo_documento_id);
        return;
    }

    $("#tablaHistorial tbody").html(`
        <tr>
            <td colspan="5" class="text-center">Cargando historial...</td>
        </tr>
    `);

    $.ajax({
        url: "/modulos/documentos_empresas/acciones/listar_historial_empresa.php",
        type: "POST",
        data: {
            empresa_id: empresa_id,
            tipo_documento_id: tipo_documento_id
        },
        dataType: "json",

        success: function (resp) {

            if (!resp.ok) {
                console.error("Respuesta inválida:", resp);
                return;
            }

            let html = "";

            resp.historial.forEach(function (item) {

                html += `
                    <tr>
                        <td>${item.version}</td>
                        <td>${item.archivo}</td>
                        <td>${item.fecha_subida}</td>
                        <td>${item.fecha_vencimiento}</td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-preview"
                                    data-url="${item.ruta}">
                                Ver
                            </button>
                        </td>
                    </tr>
                `;
            });

            $("#tablaHistorial tbody").html(html);
            $("#modalHistorial").modal("show");
        },

        error: function (xhr) {
            console.error("Error AJAX historial:", xhr.responseText);
            $("#tablaHistorial tbody").html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        Error al cargar historial
                    </td>
                </tr>
            `);
        }
    });
}
