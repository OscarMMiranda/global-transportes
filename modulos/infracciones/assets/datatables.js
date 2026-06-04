// archivo: /modulos/infracciones/assets/datatables.js

$(document).ready(function () {

    if ($("#tablaInfracciones").length) {

        $("#tablaInfracciones").DataTable({
            processing: true,
            serverSide: false,
            ajax: "ajax/listar.php",
            columns: [
                { data: "codigo" },
                { data: "descripcion" },
                { data: "gravedad" },
                { data: "monto_base" },
                { data: "puntos" },
                { data: "entidad_nombre" },
                {
                    data: "id",
                    render: function(id){
                        return `
                            <button class="btn btn-sm btn-primary" onclick="editarInfraccion('${id}')">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarInfraccion('${id}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        `;
                    }
                }
            ]
        });

    }

});
