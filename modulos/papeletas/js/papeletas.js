// archivo: /modulos/papeletas/js/papeletas.js

$(document).ready(function() {

    cargarPapeletas();

    $("#btnBuscar").click(cargarPapeletas);
    $("#filtroVehiculo").on("keyup", cargarPapeletas);
    $("#filtroConductor").on("keyup", cargarPapeletas);
    $("#filtroEstado").on("change", cargarPapeletas);

});
