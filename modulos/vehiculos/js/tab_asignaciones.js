// =========================================================
//  ARCHIVO: /modulos/vehiculos/js/tab_asignaciones.js
//  OBJETIVO: Controlar la carga del TAB Asignaciones
//  ESTADO: Versión final, estable y corporativa
// =========================================================

console.log("🧩 tab_asignaciones.js cargado correctamente");

// ---------------------------------------------------------
// FUNCIÓN PRINCIPAL: Cargar TAB Asignaciones
// ---------------------------------------------------------
function cargarTabAsignaciones(idVehiculo) {

    console.log("🧩 Cargando TAB Asignaciones con ID:", idVehiculo);

    // Limpia contenido previo para evitar mostrar datos viejos
    $('#tab-asignaciones').html("Cargando asignaciones...");

    $.post(
        '/modulos/vehiculos/acciones/ver_asignaciones.php',
        { id: idVehiculo },
        function (resp) {

            console.log("🧩 RESPUESTA TAB Asignaciones:", resp);

            if (resp.success) {
                $('#tab-asignaciones').html(resp.html);
            } else {
                $('#tab-asignaciones').html(
                    '<div class="text-danger">Error al cargar asignaciones.</div>'
                );
            }
        },
        'json'
    )
    .fail(function(xhr){
        console.log("❌ ERROR AJAX TAB Asignaciones:", xhr.responseText);
        $('#tab-asignaciones').html(
            '<div class="text-danger">Error de comunicación con el servidor.</div>'
        );
    });
}

// ---------------------------------------------------------
// 1) CARGAR ASIGNACIONES AL ABRIR EL MODAL
// ---------------------------------------------------------
$('#modalVerVehiculo').on('shown.bs.modal', function () {

    const idVehiculo = $("#modalVerVehiculo").data("id");

    console.log("🚀 Modal abierto. ID detectado:", idVehiculo);

    // Fuerza recarga inmediata
    cargarTabAsignaciones(idVehiculo);
});

// ---------------------------------------------------------
// 2) CARGAR ASIGNACIONES AL ACTIVAR EL TAB
//    (Garantiza actualización si el usuario navega entre tabs)
// ---------------------------------------------------------
$('a[href="#tab-asignaciones"]').on('shown.bs.tab', function () {

    console.log("🔥 TAB Asignaciones ACTIVADO");

    const idVehiculo = $("#modalVerVehiculo").data("id");
    console.log("🧩 ID detectado para TAB Asignaciones:", idVehiculo);

    cargarTabAsignaciones(idVehiculo);
});

// =========================================================
// FIN DEL ARCHIVO
// =========================================================
