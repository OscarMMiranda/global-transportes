// archivo: /modulos/vehiculos/js/tab_papeletas.js

console.log("🚨 tab_papeletas.js cargado correctamente");

// ---------------------------------------------------------
// FUNCIÓN: Cargar TAB Papeletas
// ---------------------------------------------------------
function cargarTabPapeletas(idVehiculo) {
    console.log("🚨 Cargando TAB Papeletas con ID:", idVehiculo);

    $.post('/modulos/vehiculos/acciones/ver_papeletas.php', { id: idVehiculo }, function (resp) {

        console.log("🚨 RESPUESTA TAB Papeletas:", resp);

        if (resp.success) {
            $('#tab-papeletas').html(resp.html);
        } else {
            $('#tab-papeletas').html('<div class="text-danger">Error al cargar papeletas.</div>');
        }

    }, 'json')
    .fail(function(xhr){
        console.log("❌ ERROR AJAX TAB Papeletas:", xhr.responseText);
        $('#tab-papeletas').html('<div class="text-danger">Error de comunicación.</div>');
    });
}

// ---------------------------------------------------------
// CARGAR TAB PAPELETAS AL CAMBIAR DE TAB
// ---------------------------------------------------------
$('a[href="#tab-papeletas"]').on('shown.bs.tab', function () {

    console.log("🔥 TAB Papeletas ACTIVADO");

    const idVehiculo = $("#modalVerVehiculo").data("id");
    console.log("🚨 ID detectado para TAB Papeletas:", idVehiculo);

    cargarTabPapeletas(idVehiculo);
});
