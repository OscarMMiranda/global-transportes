// ======================================================
//  JS: ot_modals_ver.js
//  RESPONSABILIDAD: Modal VER OT
//  GLOBAL 2026 — Arquitectura Limpia (Versión 4.8)
// ======================================================

function verOT(id) {

    if (!id) return;

    // Abrir modal
    $("#modalVerOT").modal("show");

    // Mostrar overlay
    $("#overlay-ver-ot").removeClass("d-none");

    // Limpiar contenido previo
    $("#contenedorVerOT").html("");
    $("#tablaViajesOT tbody").html("");

    $.ajax({
        url: "/modulos/orden_trabajo/api/ot/ot_ver_api.php",
        type: "POST",
        data: { id: id },
        dataType: "json",

        success: function (r) {

            // Ocultar overlay
            $("#overlay-ver-ot").addClass("d-none");

            if (!r.ok) {
                $("#contenedorVerOT").html(
                    '<div class="msg-listado msg-listado-error">' + r.msg + '</div>'
                );
                return;
            }

            // Insertar la vista ver_ot.php
            $("#contenedorVerOT").html(r.html);


            console.log('VIAJES RECIBIDOS');
            console.log(r.viajes);

            // Llenar tabla de viajes
            cargarViajesOT(r.viajes);
        },

        error: function (xhr, status, error) {

    console.log("================================");
    console.log("STATUS");
    console.log(status);

    console.log("ERROR");
    console.log(error);

    console.log("RESPONSE");
    console.log(xhr.responseText);

    console.log("================================");

    $("#overlay-ver-ot").addClass("d-none");

    $("#contenedorVerOT").html(
        '<div class="msg-listado msg-listado-error">Error de comunicación con el servidor</div>'
    );
}
    });
}


// ======================================================
//  Cargar viajes en la tabla
// ======================================================
function cargarViajesOT(lista) {

    var tbody = $("#tablaViajesOT tbody");
    tbody.empty();

    if (!Array.isArray(lista) || lista.length === 0) {
        tbody.html('<tr><td colspan="9" class="text-center text-muted">Sin viajes registrados</td></tr>');
        return;
    }

    lista.forEach(function (v) {

        var fila = `
            <tr>
                <td>${v.id}</td>
                <td>${v.fecha}</td>
                <td>${v.semana}</td>
                <td>${v.vehiculo}</td>
                <td>${v.conductor}</td>
                <td>${v.origen}</td>
                <td>${v.destino}</td>
                <td>${v.mercaderia}</td>
                <td>${v.estado}</td>
            </tr>
        `;

        tbody.append(fila);
    });
}
