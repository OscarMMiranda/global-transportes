// archivo: /modulos/vehiculos/js/tab_documentos.js

console.log("📄 tab_documentos.js cargado correctamente");

// ---------------------------------------------------------
// FUNCIÓN: Cargar TAB Documentos
// ---------------------------------------------------------
function cargarTabDocumentos(idVehiculo) {
    console.log("📄 Cargando TAB Documentos con ID:", idVehiculo);

    $.post('/modulos/vehiculos/acciones/ver_documentos.php', { id: idVehiculo }, function (resp) {

        console.log("📄 RESPUESTA TAB Documentos:", resp);

        if (resp.success) {
            $('#tab-documentos').html(resp.html);
        } else {
            $('#tab-documentos').html('<div class="text-danger">Error al cargar documentos.</div>');
        }

    }, 'json')
    .fail(function(xhr){
        console.log("❌ ERROR AJAX TAB Documentos:", xhr.status, xhr.responseText);
        $('#tab-documentos').html('<div class="text-danger">Error de comunicación.</div>');
    });
}

// ---------------------------------------------------------
// CARGAR TAB DOCUMENTOS AL CAMBIAR DE TAB
// ---------------------------------------------------------
$('a[href="#tab-documentos"]').on('shown.bs.tab', function () {

    console.log("🔥 TAB Documentos ACTIVADO");

    const idVehiculo = $("#modalVerVehiculo").data("id");
    console.log("📄 ID detectado para TAB Documentos:", idVehiculo);

    cargarTabDocumentos(idVehiculo);
});

// ---------------------------------------------------------
// ABRIR VISOR PDF (versión estable)
// ---------------------------------------------------------
$(document).on("click", ".btn-ver-pdf", function () {
    const file = $(this).data("file");

    console.log("📄 Abriendo PDF:", file);

    $("#iframePDF").attr("src", file);
    $("#visorPDF").show(); // sin animación
});

// ---------------------------------------------------------
// CERRAR VISOR PDF
// ---------------------------------------------------------
$(document).on("click", "#cerrarVisorPDF", function () {
    $("#visorPDF").hide();
    $("#iframePDF").attr("src", "");
});
