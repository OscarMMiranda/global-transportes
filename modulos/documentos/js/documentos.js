// JavaScript para la gestión de documentos
// ----------------------------------------------
// archivo: modulos/documentos/js/documentos.js


$(document).ready(function() {
    // Inicializar DataTable
    let tabla = $('#tablaDocumentos').DataTable({
        ajax: {
            url: '/modulos/documentos/acciones/listar.php',
            data: function(d) {
                // Adjuntar filtros al request
                d.tipo_documento_id = $('#filtroTipo').val();
                d.entidad_tipo = $('#filtroEntidad').val();
                d.estado = $('#filtroEstado').val();
            }
        },
        columns: [
            { data: 'id' },
            { data: 'nombre_tipo' },
            { data: 'entidad_tipo' },
            { data: 'numero' },
            { data: 'fecha_inicio' },
            { data: 'fecha_vencimiento' },
            { data: 'estado' },
            { data: 'acciones' }
        ],
        responsive: true,
        language: {
            url: '/assets/js/datatables.spanish.json'
        }
    });

    // Aplicar filtros
    $('#btnAplicarFiltros').on('click', function() {
        tabla.ajax.reload();
    });

    // Reset filtros
    $('#btnResetFiltros').on('click', function() {
        $('#formFiltros')[0].reset();
        tabla.ajax.reload();
    });

    // Ver documento
    $('#tablaDocumentos').on('click', '.btn-ver', function() {
        let id = $(this).data('id');
        $.get('/modulos/documentos/acciones/ver.php', { id }, function(resp) {
            if (resp.success) {
                $('#modalVer .modal-body').html(resp.data.html);
                $('#modalVer').modal('show');
            } else {
                alert(resp.error || 'Error al cargar documento');
            }
        }, 'json');
    });

    // Subir documento (abrir modal)
    $('#btnNuevoDocumento').on('click', function() {
        $('#modalSubir').modal('show');
    });

    // Guardar documento
    $('#formSubirDocumento').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: '/modulos/documentos/acciones/guardar.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#modalSubir').modal('hide');
                    tabla.ajax.reload();
                } else {
                    alert(resp.error || 'Error al guardar documento');
                }
            }
        });
    });

    // Eliminar documento
    $('#tablaDocumentos').on('click', '.btn-eliminar', function() {
        if (!confirm('¿Seguro que deseas eliminar este documento?')) return;
        let id = $(this).data('id');
        $.post('/modulos/documentos/acciones/eliminar.php', { id }, function(resp) {
            if (resp.success) {
                tabla.ajax.reload();
            } else {
                alert(resp.error || 'Error al eliminar documento');
            }
        }, 'json');
    });
});
