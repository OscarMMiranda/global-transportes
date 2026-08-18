// archivo: /modulos/papeletas/js/papeletas.filtros.js

$("#btnBuscar").click(cargarPapeletas);
$("#filtroVehiculo").on("keyup", cargarPapeletas);
$("#filtroConductor").on("keyup", cargarPapeletas);
$("#filtroEstado").on("change", cargarPapeletas);
