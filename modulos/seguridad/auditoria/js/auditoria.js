// archivo: /modulos/seguridad/auditoria/auditoria.js

$(document).ready(function() {

    cargarUsuarios();
    cargarModulos();
    cargarTabla();

    $('#btnBuscar').on('click', function() {
        $('#tablaAuditoria').DataTable().ajax.reload();
    });

    $('#btnLimpiar').on('click', function() {
        $('#filtro_usuario').val('');
        $('#filtro_modulo').val('');
        $('#filtro_accion').val('');
        $('#filtro_desde').val('');
        $('#filtro_hasta').val('');
        $('#tablaAuditoria').DataTable().ajax.reload();
    });

});

function cargarUsuarios() {
    $.ajax({
        url: 'acciones/listar_usuarios.php',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if (!resp.ok) return;

            var $sel = $('#filtro_usuario');
            $sel.empty();
            $sel.append('<option value="">Todos</option>');

            $.each(resp.data, function(i, u) {
                $sel.append('<option value="' + u.id + '">' + u.nombre + '</option>');
            });
        }
    });
}

function cargarModulos() {
    $.ajax({
        url: 'acciones/listar_modulos.php',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            if (!resp.ok) return;

            var $sel = $('#filtro_modulo');
            $sel.empty();
            $sel.append('<option value="">Todos</option>');

            $.each(resp.data, function(i, modulo) {
                $sel.append('<option value="' + modulo + '">' + modulo + '</option>');
            });
        }
    });
}

function cargarTabla() {
    $('#tablaAuditoria').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'acciones/listar_auditoria.php',
            type: 'POST',
            data: function(d) {
                d.usuario_id = $('#filtro_usuario').val();
                d.modulo     = $('#filtro_modulo').val();
                d.accion     = $('#filtro_accion').val();
                d.desde      = $('#filtro_desde').val();
                d.hasta      = $('#filtro_hasta').val();
            }
        },
        pageLength: 20,
        order: [[0, 'desc']],
        columns: [
            { data: 'id' },
            { data: 'fecha' },
            { data: 'usuario' },
            { data: 'modulo' },
            { data: 'accion' },
            { data: 'registro_id' },
            { data: 'descripcion' },
            { data: 'ip' }
        ]
    });
}