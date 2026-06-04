// =========================================================
//  ARCHIVO: /modulos/vehiculos/js/tab_mantenimientos.js
//  OBJETIVO: Controlar la carga del TAB Mantenimientos
//  ESTADO: Versión final, estable y corporativa
// =========================================================

console.log("🧩 tab_mantenimientos.js cargado correctamente");

// ---------------------------------------------------------
// FUNCIÓN PRINCIPAL: Cargar TAB Mantenimientos
// ---------------------------------------------------------
function cargarTabMantenimientos(idVehiculo) {

    console.log("🧩 Cargando TAB Mantenimientos con ID:", idVehiculo);

    // Limpia contenido previo para evitar mostrar datos viejos
    $('#tab-mantenimientos').html("Cargando mantenimientos...");

    $.post(
        '/modulos/vehiculos/acciones/ver_mantenimientos.php',
        { id: idVehiculo },
        function (resp) {

            console.log("🧩 RESPUESTA TAB Mantenimientos:", resp);

            if (resp.success) {
                $('#tab-mantenimientos').html(resp.html);
            } else {
                $('#tab-mantenimientos').html(
                    '<div class="text-danger">Error al cargar mantenimientos.</div>'
                );
            }
        },
        'json'
    )
    .fail(function(xhr){
        console.log("❌ ERROR AJAX TAB Mantenimientos:", xhr.responseText);
        $('#tab-mantenimientos').html(
            '<div class="text-danger">Error de comunicación con el servidor.</div>'
        );
    });
}

// ---------------------------------------------------------
// 1) CARGAR MANTENIMIENTOS AL ABRIR EL MODAL
// ---------------------------------------------------------
$('#modalVerVehiculo').on('shown.bs.modal', function () {

    const idVehiculo = $("#modalVerVehiculo").data("id");

    console.log("🚀 Modal abierto. ID detectado:", idVehiculo);

    // Fuerza recarga inmediata
    cargarTabMantenimientos(idVehiculo);
});

// ---------------------------------------------------------
// 2) CARGAR MANTENIMIENTOS AL ACTIVAR EL TAB
//    (Garantiza actualización si el usuario navega entre tabs)
// ---------------------------------------------------------
$('a[href="#tab-mantenimientos"]').on('shown.bs.tab', function () {

    console.log("🔥 TAB Mantenimientos ACTIVADO");

    const idVehiculo = $("#modalVerVehiculo").data("id");
    console.log("🧩 ID detectado para TAB Mantenimientos:", idVehiculo);

    cargarTabMantenimientos(idVehiculo);
});

// =========================================================
// FIN DEL ARCHIVO
// =========================================================
