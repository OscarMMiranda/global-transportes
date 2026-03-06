// archivo: /modulos/empleados/assets/datatables.js

$(document).ready(function () {

    // ===============================
    // TABLA DE ACTIVOS
    // ===============================
    window.tablaEmpleados = $('#tblActivos').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/modulos/empleados/acciones/listar.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'nombres' },
            { data: 'apellidos' },
            { data: 'dni' },
            { data: 'empresa' },
            { data: 'roles' },
            {
                data: null,
                orderable: false,
                render: row => 
                `
                    <button class="btn btn-sm btn-primary" onclick="Empleados.abrirEditar(${row.id})">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <button class="btn btn-sm btn-danger btnEliminar" data-id="${row.id}">
                        <i class="fa-solid fa-trash"></i>
                    </button>

                    <button class="btn btn-sm btn-info btnVer" data-id="${row.id}">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    <button class="btn btn-sm btn-warning btnHistorial" data-id="${row.id}">
                        <i class="fa-solid fa-scroll"></i>
                    </button>
                `
            }
        ],
       language: {
    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
}

    });

    // ===============================
    // TABLA DE INACTIVOS
    // ===============================
    Empleados.tablaInactivos = $('#tblInactivos').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/modulos/empleados/acciones/listar_inactivos.php',
            type: 'POST'
        },
        columns: [
            { data: 'id' },
            { data: 'nombres' },
            { data: 'apellidos' },
            { data: 'dni' },
            { data: 'empresa' },
            { data: 'roles' },
            {
                data: null,
                orderable: false,
                render: row => `
                    <button class="btn btn-sm btn-info btnVer" data-id="${row.id}">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    <button class="btn btn-sm btn-warning btnHistorial" data-id="${row.id}">
                        <i class="fa-solid fa-scroll"></i>
                    </button>

                    <button class="btn btn-sm btn-success btnRestaurar" data-id="${row.id}">
                        <i class="fa-solid fa-rotate-left"></i>
                    </button>
                `
            }
        ],
        language: {
    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
}

    });

    // ===============================
    // EVENTO: ELIMINAR (SOFT DELETE)
    // ===============================
    $('#tblActivos').on('click', '.btnEliminar', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Desactivar empleado?',
            text: 'El empleado pasará a la lista de inactivos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then(res => {
            if (res.isConfirmed) {
                $.post('/modulos/empleados/acciones/desactivar.php', { id }, function (resp) {
                    if (resp.success) {
                        Swal.fire('Desactivado', 'Empleado movido a inactivos', 'success');
                        tablaEmpleados.ajax.reload(null, false);
                        Empleados.tablaInactivos.ajax.reload(null, false);
                    }
                }, 'json');
            }
        });
    });

    // ===============================
    // EVENTO: RESTAURAR
    // ===============================
    $('#tblInactivos').on('click', '.btnRestaurar', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Restaurar empleado?',
            text: 'El empleado volverá a estar activo.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar'
        }).then(res => {
            if (res.isConfirmed) {
                $.post('/modulos/empleados/acciones/reactivar.php', { id }, function (resp) {
                    if (resp.success) {
                        Swal.fire('Restaurado', 'Empleado activado correctamente', 'success');
                        tablaEmpleados.ajax.reload(null, false);
                        Empleados.tablaInactivos.ajax.reload(null, false);
                    }
                }, 'json');
            }
        });
    });

});
