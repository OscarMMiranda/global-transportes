// archivo: /modulos/empleados/assets/tabla.js

window.Empleados = window.Empleados || {};

(function () {

    Empleados.tabla = $('#tablaEmpleados').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: '/modulos/empleados/acciones/listar.php',
            type: 'GET',
            dataSrc: 'data'
        },

        columns: [

            { data: 'id' },

            {
                data: 'foto',
                render: function (foto) {
                    if (!foto) {
                        return '<img src="/assets/img/user.png" class="img-thumbnail" style="height:45px;">';
                    }
                    return '<img src="' + foto + '" class="img-thumbnail" style="height:45px;">';
                }
            },

            { data: 'nombres' },
            { data: 'apellidos' },
            { data: 'dni' },
            { data: 'telefono' },
            { data: 'correo' },

            {
                data: 'distrito_nombre',
                render: function (d) {
                    return d ? d : '-';
                }
            },

            { data: 'fecha_ingreso' },

            {
                data: 'estado',
                render: function (estado) {
                    let color =
                        estado === 'Activo' ? 'success' :
                        estado === 'Inactivo' ? 'secondary' :
                        'warning';

                    return '<span class="badge bg-' + color + '">' + estado + '</span>';
                }
            },

            {
                data: 'roles',
                render: function (roles) {
                    if (!roles || roles.length === 0) return '<span class="text-muted">Sin roles</span>';

                    return roles.map(r => '<span class="badge bg-info me-1">' + r + '</span>').join('');
                }
            },

            {
                data: null,
                orderable: false,
                render: function (row) {
                    return `
                        <button class="btn btn-sm btn-primary btnEditar" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-sm btn-danger btnEliminar" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],

        language: {
            url: ''
        },

        scrollX: true,
        pageLength: 10
    });

    // ============================================================
    // EVENTO: NUEVO EMPLEADO
    // ============================================================
    $('#btnNuevoEmpleado').on('click', function () {
        $('#modalEmpleado').attr('data-modo', 'crear').modal('show');
    });

    // ============================================================
    // EVENTO: EDITAR EMPLEADO
    // ============================================================
    $('#tablaEmpleados').on('click', '.btnEditar', function () {
        const id = $(this).data('id');
        Empleados.abrirEditar(id);
    });

    // ============================================================
    // EVENTO: ELIMINAR EMPLEADO
    // ============================================================
    $('#tablaEmpleados').on('click', '.btnEliminar', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar empleado?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(res => {
            if (res.isConfirmed) {
                $.post('/modulos/empleados/acciones/eliminar.php', { id }, function (resp) {
                    if (resp.success) {
                        Swal.fire('Eliminado', 'Empleado eliminado correctamente', 'success');
                        Empleados.tabla.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', resp.error || 'No se pudo eliminar', 'error');
                    }
                }, 'json');
            }
        });
    });

})();
