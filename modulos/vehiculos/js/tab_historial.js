// =========================================================
//  ARCHIVO: /modulos/vehiculos/js/tab_historial.js
//  OBJETIVO: Controlar la carga del TAB Historial
//  ESTADO: Versión final, estable y corporativa
// =========================================================

console.log("📜 tab_historial.js cargado correctamente");

// ---------------------------------------------------------
// FUNCIÓN PRINCIPAL: Cargar TAB Historial
// ---------------------------------------------------------
function cargarTabHistorial(idVehiculo) {

    console.log("📜 Cargando TAB Historial con ID:", idVehiculo);

    // Limpia contenido previo para evitar mostrar datos viejos
    $('#tab-historial').html("Cargando historial...");

    $.post(
        '/modulos/vehiculos/acciones/ver_historial.php',
        { id: idVehiculo },
        function (resp) {

            console.log("📜 RESPUESTA TAB Historial:", resp);

            if (resp.success) {
                $('#tab-historial').html(resp.html);
            } else {
                $('#tab-historial').html(
                    '<div class="text-danger">Error al cargar historial.</div>'
                );
            }
        },
        'json'
    )
    .fail(function(xhr){
        console.log("❌ ERROR AJAX TAB Historial:", xhr.responseText);
        $('#tab-historial').html(
            '<div class="text-danger">Error de comunicación con el servidor.</div>'
        );
    });
}

// ---------------------------------------------------------
// 1) CARGAR HISTORIAL AL ABRIR EL MODAL
// ---------------------------------------------------------
$('#modalVerVehiculo').on('shown.bs.modal', function () {

    const idVehiculo = $("#modalVerVehiculo").data("id");

    console.log("🚀 Modal abierto. ID detectado:", idVehiculo);

    cargarTabHistorial(idVehiculo);
});

// ---------------------------------------------------------
// 2) CARGAR HISTORIAL AL ACTIVAR EL TAB
// ---------------------------------------------------------
$('a[href="#tab-historial"]').on('shown.bs.tab', function () {

    console.log("🔥 TAB Historial ACTIVADO");

    const idVehiculo = $("#modalVerVehiculo").data("id");
    console.log("📜 ID detectado para TAB Historial:", idVehiculo);

    cargarTabHistorial(idVehiculo);
});

// =========================================================
// FIN DEL ARCHIVO
// =========================================================
