// /modulos/documentos_empresas/js/documentos_tabs.js
$(document).ready(function () {

    console.log("documentos_tabs.js cargado correctamente");

    const urlParams = new URLSearchParams(window.location.search);
    const empresa_id = urlParams.get("id");

    if (!empresa_id) {
        console.error("No se encontró empresa_id en la URL");
        return;
    }

    // ============================================================
    // CARGAR CATEGORÍAS
    // ============================================================
    $.ajax({
        url: "../acciones/listar_categorias_documentos.php",
        type: "GET",
        dataType: "json", // ← IMPORTANTE
        data: { entidad_tipo: "empresa" },

        success: function (categorias) {

            console.log("Categorías recibidas:", categorias);

            if (!Array.isArray(categorias) || categorias.length === 0) {
                $("#contenedorTabs").html("<div class='alert alert-warning'>No hay categorías configuradas.</div>");
                return;
            }

            let htmlTabs = `<ul class="nav nav-tabs" id="tabsDocumentos" role="tablist">`;
            let htmlContent = `<div class="tab-content" id="tabsContent">`;

            categorias.forEach((cat, index) => {

                const active = index === 0 ? "active" : "";
                const show = index === 0 ? "show active" : "";

                htmlTabs += `
                    <li class="nav-item" role="presentation">
                        <button class="nav-link ${active}" id="tab-${cat.id}"
                            data-bs-toggle="tab"
                            data-bs-target="#contenido-${cat.id}"
                            type="button" role="tab">
                            ${cat.nombre}
                        </button>
                    </li>
                `;

                htmlContent += `
                    <div class="tab-pane fade ${show}" id="contenido-${cat.id}" role="tabpanel">
                        <table class="table table-bordered tabla-documentos"
                            id="tabla-${cat.id}">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Número</th>
                                    <th>Inicio</th>
                                    <th>Vencimiento</th>
                                    <th>Días</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                `;
            });

            htmlTabs += `</ul>`;
            htmlContent += `</div>`;

            $("#contenedorTabs").html(htmlTabs + htmlContent);

            inicializarTablas(empresa_id, categorias);
        },

        error: function (xhr) {
            console.error("Error cargando categorías:", xhr.responseText);
        }
    });

});

// ============================================================
// INICIALIZAR TABLAS POR CATEGORÍA
// ============================================================
function inicializarTablas(empresa_id, categorias) {

    categorias.forEach(cat => {

        $(`#tabla-${cat.id}`).DataTable({
            ajax: {
                url: "../acciones/listar_documentos_empresa_completo.php",
                type: "GET",
                data: {
                    empresa_id: empresa_id,
                    categoria_id: cat.id
                }
            },
            columns: [
                { data: "tipo_documento" },
                { data: "numero" },
                { data: "fecha_inicio" },
                { data: "fecha_vencimiento" },
                { data: "dias_restantes" },
                { data: "estado_html" },
                { data: "acciones" }
            ],
            pageLength: 50,
            ordering: false,
            searching: false
        });

    });

    // Ajustar columnas al cambiar de pestaña
    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function () {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
}

