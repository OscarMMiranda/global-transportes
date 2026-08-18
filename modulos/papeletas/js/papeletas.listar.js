// archivo: /modulos/papeletas/js/papeletas.listar.js

function cargarPapeletas() {

    $("#contenedorTablaPapeletas").html(
        '<div class="text-center text-muted py-5">Cargando papeletas...</div>'
    );

    $.post('/modulos/papeletas/acciones/listar.php', {
        vehiculo: $("#filtroVehiculo").val(),
        conductor: $("#filtroConductor").val(),
        estado: $("#filtroEstado").val()
    }, function(html) {

        // Insertar tabla generada
        $("#contenedorTablaPapeletas").html(html);

        // Destruir DataTable previo si existe
        if ($.fn.DataTable.isDataTable("#tablaPapeletas")) {
            $("#tablaPapeletas").DataTable().destroy();
        }

        // *** CLAVE: asegurar que DataTables siempre reciba estructura completa ***
        // Esto evita el error "_DT_CellIndex" cuando el filtro devuelve 0 filas.
        $("#tablaPapeletas").find("tbody tr").each(function() {
            const tdCount = $(this).find("td").length;
            if (tdCount < 7) {
                // Completar columnas faltantes
                for (let i = tdCount; i < 7; i++) {
                    $(this).append("<td></td>");
                }
            }
        });

        // Inicializar DataTable
        $("#tablaPapeletas").DataTable({
            pageLength: 25,
            order: [[0, "desc"]],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });

    });
}
