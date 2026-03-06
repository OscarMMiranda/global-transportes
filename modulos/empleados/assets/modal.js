// archivo: /modulos/empleados/assets/modal.js
console.log("modal.js de empleados cargado correctamente");

window.Empleados = window.Empleados || {};

// ============================================================
// Cargar empresas
// ============================================================
Empleados.cargarEmpresas = function (callback) {

    $.ajax({
        url: '/modulos/empleados/acciones/listar_empresas_simple.php',
        type: 'GET',
        dataType: 'json',
        success: function (res) {

            var sel = $('#empresa_id');
            sel.empty().append('<option value="">Seleccione...</option>');

            if (res.data) {
                $.each(res.data, function (i, e) {
                    sel.append('<option value="' + e.id + '">' + e.razon_social + '</option>');
                });
            }

            if (typeof callback === 'function') callback();
        }
    });
};

// ============================================================
// Cargar roles
// ============================================================
Empleados.cargarRoles = function (empleadoId) {

    $.ajax({
        url: '/modulos/empleados/acciones/listar_roles.php',
        type: 'GET',
        data: { id: empleadoId || 0 },
        dataType: 'json',
        success: function (res) {

            var cont = $('#contenedor_roles');
            cont.empty();

            if (!res.data || res.data.length === 0) {
                cont.html('<span class="text-muted">No hay roles registrados</span>');
                return;
            }

            $.each(res.data, function (i, r) {

                var checked = r.asignado ? 'checked' : '';

                cont.append(
                    '<div class="form-check">' +
                        '<input class="form-check-input" type="checkbox" name="roles[]" value="' + r.id + '" ' + checked + '>' +
                        '<label class="form-check-label">' + r.nombre + '</label>' +
                    '</div>'
                );
            });
        }
    });
};

// ============================================================
// ABRIR MODAL EN MODO EDITAR
// ============================================================
Empleados.abrirEditar = function (id) {

    var modal = new bootstrap.Modal(document.getElementById('modalEmpleado'));

    $('#modalEmpleado').attr('data-modo', 'editar');
    $('#formEmpleado')[0].reset();
    $('#preview_foto_empleado').hide().attr('src', '');
    $('#e_id').val('');
    $('#foto_actual').val('');

    Empleados.cargarEmpresas(function () {

        $.ajax({
            url: '/modulos/empleados/acciones/obtener.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function (resp) {

                if (!resp.success) {
                    Swal.fire('Error', resp.message || 'No se pudo obtener datos', 'error');
                    return;
                }

                var e = resp.data;

                $('#e_id').val(e.id);
                $('#e_nombres').val(e.nombres);
                $('#e_apellidos').val(e.apellidos);
                $('#e_dni').val(e.dni);
                $('#e_telefono').val(e.telefono);
                $('#e_correo').val(e.correo);
                $('#e_direccion').val(e.direccion);
                $('#empresa_id').val(e.empresa_id);
                $('#e_fecha_ingreso').val(e.fecha_ingreso);
                $('#e_estado').val(e.estado);

                if (e.foto) {
                    $('#preview_foto_empleado').attr('src', e.foto).show();
                    $('#foto_actual').val(e.foto);
                }

                // UBIGEO en cascada
                Ubigeo.cargarDepartamentos('#departamento_id', function () {

                    $('#departamento_id').val(e.departamento_id);

                    if (e.departamento_id) {
                        Ubigeo.cargarProvincias('#provincia_id', e.departamento_id, function () {

                            $('#provincia_id').val(e.provincia_id);

                            if (e.provincia_id) {
                                Ubigeo.cargarDistritos('#distrito_id', e.provincia_id, function () {

                                    $('#distrito_id').val(e.distrito_id);

                                });
                            }
                        });
                    }
                });

                Empleados.cargarRoles(e.id);
            }
        });
    });

    modal.show();
};

// ============================================================
// ABRIR MODAL EN MODO CREAR
// ============================================================
$('#modalEmpleado').on('show.bs.modal', function () {

    var modo = $(this).attr('data-modo');

    $('#formEmpleado')[0].reset();
    $('#preview_foto_empleado').hide().attr('src', '');
    $('#e_id').val('');
    $('#foto_actual').val('');

    Empleados.cargarEmpresas();
    Empleados.cargarRoles(0);

    if (modo === 'crear') {

        // Cargar departamentos SIEMPRE al crear
        Ubigeo.cargarDepartamentos('#departamento_id', function () {
            $('#provincia_id').html('<option value="">Seleccione provincia</option>');
            $('#distrito_id').html('<option value="">Seleccione distrito</option>');
        });

        // Reset selects
        $('#departamento_id').val('');
        $('#provincia_id').val('');
        $('#distrito_id').val('');
    }
});

// ============================================================
// ABRIR MODAL DE HISTORIAL
// ============================================================
Empleados.abrirHistorial = function(id) {

    // Cargar datos por AJAX
    $.post('/modulos/empleados/acciones/historial.php', { id }, function(resp) {

        if (!resp.success) {
            Swal.fire('Error', resp.message || 'No se pudo cargar el historial', 'error');
            return;
        }

        // Insertar datos en el modal
        $('#historialContenido').html(resp.html);

        // Mostrar modal
        $('#modalHistorial').modal('show');

    }, 'json');
};

