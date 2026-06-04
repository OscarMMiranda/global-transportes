// archivo: modulos/vehiculos/js/tab_ficha.js

console.log("📗 tab_ficha.js (VER) cargado correctamente");

// Cargar cuando el usuario hace clic en el tab
$('a[href="#tab-ficha-ver"]').on('shown.bs.tab', function () {
    const idVehiculo = $("#modalVerVehiculo").data("id");
    cargarTabFichaVer(idVehiculo);
});

// Cargar si el modal abre y el tab ya está activo
$('#modalVerVehiculo').on('shown.bs.modal', function () {
    if ($('a[href="#tab-ficha-ver"]').hasClass('active')) {
        const idVehiculo = $("#modalVerVehiculo").data("id");
        cargarTabFichaVer(idVehiculo);
    }
});

// ---------------------------------------------------------
// FUNCIÓN: Cargar TAB Ficha Técnica (MODAL VER)
// ---------------------------------------------------------
function cargarTabFichaVer(idVehiculo) {
    console.log("📗 Cargando TAB Ficha Técnica (VER) con ID:", idVehiculo);

    $("#tab-ficha-ver").html(`
        <div class="text-center text-muted py-5">
            <div class="spinner-border text-primary mb-3"></div>
            <div>Cargando ficha técnica...</div>
        </div>
    `);

    $.post('/modulos/vehiculos/acciones/ver_ficha.php', { id: idVehiculo }, function (resp) {

        if (resp.success) {
            $("#tab-ficha-ver").html(resp.html);
        } else {
            $("#tab-ficha-ver").html('<div class="text-danger">Error al cargar ficha técnica.</div>');
        }

    }, 'json')
    .fail(function(xhr){
        $("#tab-ficha-ver").html('<div class="text-danger">Error de comunicación.</div>');
    });
}
