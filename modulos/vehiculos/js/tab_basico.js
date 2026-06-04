// archivo: /modulos/vehiculos/js/tab_basico.js

console.log("📘 tab_basico.js cargado correctamente");

// ---------------------------------------------------------
// FUNCIÓN: Cargar TAB Básico
// ---------------------------------------------------------
function cargarTabBasico(idVehiculo) {
    console.log("📘 Cargando TAB Básico con ID:", idVehiculo);

    $.post('/modulos/vehiculos/acciones/ver_basico.php', { id: idVehiculo }, function (resp) {

        console.log("📘 RESPUESTA TAB Básico:", resp);

        if (resp.success) {
            $('#tab-basico').html(resp.html);
        } else {
            $('#tab-basico').html('<div class="text-danger">Error al cargar datos.</div>');
        }

    }, 'json')
    .fail(function(xhr){
        console.log("❌ ERROR AJAX TAB Básico:", xhr.responseText);
        $('#tab-basico').html('<div class="text-danger">Error de comunicación.</div>');
    });
}

// ---------------------------------------------------------
// CARGAR TAB BÁSICO AL ABRIR EL MODAL
// ---------------------------------------------------------
$('#modalVerVehiculo').on('shown.bs.modal', function () {

    console.log("🔥 EVENTO shown.bs.modal (TAB Básico)");
    
    const idVehiculo = $(this).data('id');
    console.log("📘 ID detectado para TAB Básico:", idVehiculo);

    cargarTabBasico(idVehiculo);
});
