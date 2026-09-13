// Archivo: /modulos/orden_trabajo/js/ver_ot.js

// Evento: botón VER dentro de la tabla principal
$("#tablaOT").on("click", ".btn-ver", function () {

    let id = $(this).data("id");

    // Mostrar mensaje inicial mientras carga
    $("#bloqueDatosOT").html("<div class='p-3 text-center text-muted'>Cargando información...</div>");

    // Cargar información principal de la OT
    $.post("/modulos/orden_trabajo/controllers/VerController.php", { id: id }, function (html) {

        // Insertar la vista ver.php dentro del bloque
        $("#bloqueDatosOT").html(html);

        // Cargar viajes asociados a la OT
        cargarViajesOT(id);

        // Mostrar el modal
        $("#modalVerOT").modal("show");
    });
});


// =======================================
// CARGAR VIAJES DE LA OT
// =======================================
function cargarViajesOT(ot_id) {

    $.post(
        "/modulos/orden_trabajo/controllers/ListarViajesOTController.php",
        { orden_trabajo_id: ot_id },
        function (r) {

            let html = "";

            // Si no hay viajes
            if (!r.ok || r.data.length === 0) {
                html = `
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Sin viajes registrados
                        </td>
                    </tr>
                `;
                $("#tablaViajesOT tbody").html(html);
                return;
            }

            // Construir filas
            r.data.forEach(v => {

                html += `
                    <tr>
                        <td>${v.numero_viaje}</td>
                        <td>${v.fecha}</td>
                        <td>${v.semana}</td>
                        <td>${v.vehiculo}</td>
                        <td>${v.conductor}</td>
                        <td>${v.origen}</td>
                        <td>${v.destino}</td>
                        <td>${v.tipo_mercaderia}</td>
                        <td>${v.estado}</td>
                    </tr>
                `;
            });

            // Insertar filas en la tabla
            $("#tablaViajesOT tbody").html(html);
        },
        "json"
    );
}
