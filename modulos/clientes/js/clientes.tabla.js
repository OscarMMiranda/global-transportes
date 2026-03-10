// archivo: /modulos/clientes/js/clientes.tabla.js

let tablaClientes = null;

function inicializarTablaClientes() {

    tablaClientes = $('#tablaClientes').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],

        ajax: {
            url: "index.php?action=api&type=list",
            type: "GET",
            dataSrc: ""
        },

        columns: [
            { data: "id" },
            { data: "nombre" },
            { data: "ruc" },
            { data: "direccion" },
            { data: "correo" },
            { data: "telefono" },

            {
                data: null,
                orderable: false,
                render: function (data) {

                    return `
                        <button class="btn btn-primary btn-sm"
                                onclick="abrirModalCliente(${data.id})">
                            <i class="fa fa-edit"></i> Editar
                        </button>

                        <a href="index.php?action=delete&id=${data.id}"
                           class="btn btn-danger btn-sm ms-1"
                           onclick="return confirm('¿Eliminar este cliente?');">
                            <i class="fa fa-trash"></i> Eliminar
                        </a>
                    `;
                }
            }
        ],

        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });
}

// Recargar tabla desde otros scripts
function recargarTablaClientes() {
    if (tablaClientes) {
        tablaClientes.ajax.reload(null, false);
    }
}
