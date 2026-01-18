// archivo: /modulos/mantenimiento/tipo_vehiculo/js/datatables.js
console.log("📦 datatables.js inicializado");

// ---------------------------------------------------------
// CONFIGURACIÓN DE IDIOMA (sin archivo externo)
// ---------------------------------------------------------
const idiomaDataTables = {
    processing: "Procesando...",
    lengthMenu: "Mostrar _MENU_ registros",
    zeroRecords: "No se encontraron resultados",
    emptyTable: "Ningún dato disponible en esta tabla",
    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
    infoEmpty: "Mostrando 0 a 0 de 0 registros",
    infoFiltered: "(filtrado de un total de _MAX_ registros)",
    search: "Buscar:",
    paginate: {
        first: "Primero",
        last: "Último",
        next: "Siguiente",
        previous: "Anterior"
    }
};

// ---------------------------------------------------------
// TABLA: ACTIVOS
// ---------------------------------------------------------
var tablaActivos = $("#tblActivosTipoVehiculo").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/listar_activos.php",
        type: "POST",
        error: function () {
            notifyError("Error", "No se pudo cargar la tabla de activos.");
        }
    },
    columns: [
        { data: "num" },
        { data: "nombre" },
        { data: "descripcion" },
        { data: "estado" },
        { data: "acciones", orderable: false, searchable: false }
    ],
    order: [[1, "asc"]],
    language: idiomaDataTables
});

// ---------------------------------------------------------
// TABLA: INACTIVOS
// ---------------------------------------------------------
var tablaInactivos = $("#tblInactivosTipoVehiculo").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "/modulos/mantenimiento/tipo_vehiculo/acciones/listar_inactivos.php",
        type: "POST",
        error: function () {
            notifyError("Error", "No se pudo cargar la tabla de inactivos.");
        }
    },
    columns: [
        { data: "num" },
        { data: "nombre" },
        { data: "descripcion" },
        { data: "estado" },
        { data: "acciones", orderable: false, searchable: false }
    ],
    order: [[1, "asc"]],
    language: idiomaDataTables
});