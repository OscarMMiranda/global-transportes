// archivo: /modulos/infracciones/assets/datatables.js
// Inicialización estable del DataTable del módulo Infracciones
// OMMZ — versión final

$(document).ready(function () {

    // Evitar doble inicialización
    if (!$.fn.DataTable.isDataTable("#tablaInfracciones")) {

        $("#tablaInfracciones").DataTable({

            processing: true,
            serverSide: false,

            // Cargar datos desde AJAX
            ajax: "ajax/listar.php",

            // Evita que DataTables destruya tus anchos
            autoWidth: false,
            scrollX: true,

            // Definir columnas y anchos reales
            columnDefs: [
                { width: "60px", targets: 0 },     // Código
                { width: "200px", targets: 1 },    // Descripción (limitada)
                { width: "80px", targets: 2 },     // Gravedad
                { width: "90px", targets: 3 },     // Monto
                { width: "70px", targets: 4 },     // Puntos
                { width: "150px", targets: 5 },    // Entidad
                { width: "200px", targets: 6 }     // Acciones
            ],

            columns: [
                { data: "codigo" },

                // DESCRIPCIÓN LIMITADA
                {
                    data: "descripcion",
                    render: function (data) {
                        if (!data) return "";
                        return `<span class="col-descripcion" title="${data}">${data}</span>`;
                    }
                },

                { data: "gravedad" },
                { data: "monto_base" },
                { data: "puntos" },
                { data: "entidad_nombre" },

                // ACCIONES
                {
                    data: "id",
                    orderable: false,
                    searchable: false,
                    render: function(id){
                        return `
                            <button class="btn btn-sm btn-info" onclick="verInfraccion('${id}')">
                                <i class="fa fa-eye"></i>
                            </button>

                            <button class="btn btn-sm btn-primary" onclick="editarInfraccion('${id}')">
                                <i class="fa fa-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-danger" onclick="eliminarInfraccion('${id}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ],

            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    }

});
