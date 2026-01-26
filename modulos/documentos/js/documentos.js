// JavaScript para la gestión de documentos
// ----------------------------------------------
// archivo: modulos/documentos/js/documentos.js

$(document).ready(function() {

    // ============================================================
    // 1. CARGA DE TABLA
    // ============================================================
    let tabla = $('#tablaDocumentos').DataTable({
        ajax: {
            url: '/modulos/documentos/acciones/listar.php',
            type: 'POST',
            dataSrc: 'data',
            data: function(d) {
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
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

    // ============================================================
    // 2. FILTROS
    // ============================================================
    $('#btnAplicarFiltros').on('click', function() {
        tabla.ajax.reload();
    });

    $('#btnResetFiltros').on('click', function() {
        $('#formFiltros')[0].reset();
        tabla.ajax.reload();
    });

    // ============================================================
    // 3. VER DOCUMENTO
    // ============================================================
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

    // ============================================================
    // 4. ABRIR MODAL DE SUBIDA
    // ============================================================
    $('#btnNuevoDocumento').on('click', function() {
        $('#formSubirDocumento')[0].reset();
        $('#campos_dinamicos').html('');
        $('#modalSubir').modal('show');
    });

    // ============================================================
    // 5. CARGAR ENTIDADES SEGÚN TIPO (vehículo → placas, etc.)
    // ============================================================
    $('#entidad_tipo').on('change', function() {
        let tipo = $(this).val();

        if (tipo === '') {
            $('#entidad_id').html('<option value="">Seleccione una entidad...</option>');
            return;
        }

        $.get('/modulos/documentos/acciones/get_entidades.php', { tipo }, function(resp) {
            $('#entidad_id').html(resp.html);
        }, 'json');
    });

    // ============================================================
    // 6. CARGAR CAMPOS SEGÚN TIPO DE DOCUMENTO (SOAT, licencia, etc.)
    // ============================================================
    $('#tipo_documento_id').on('change', function() {
        let tipo = $(this).val();

        if (tipo === '') {
            $('#campos_dinamicos').html('');
            return;
        }

        $.get('/modulos/documentos/acciones/get_campos_tipo.php', { tipo }, function(resp) {
            $('#campos_dinamicos').html(resp.html);
        }, 'json');
    });

    // ============================================================
    // 7. GUARDAR DOCUMENTO
    // ============================================================
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

    // ============================================================
    // 8. ELIMINAR DOCUMENTO
    // ============================================================
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


// ============================================================
// 5.1 CAMBIAR LABEL SEGÚN ENTIDAD (vehículo → Placa, etc.)
// ============================================================
$('#entidad_tipo').on('change', function() {
    let tipo = $(this).val();

    // Cambiar label dinámico
    let label = "Seleccione entidad";

    if (tipo === "vehiculo") label = "Placa del vehículo";
    if (tipo === "conductor") label = "DNI del conductor";
    if (tipo === "empleado") label = "Empleado";
    if (tipo === "empresa") label = "Empresa";

    $('#label_entidad_id').text(label);
});


function cargarTiposDocumento() {
    $.get('/modulos/documentos/acciones/get_tipos_documento.php', function(resp) {
        $('#tipo_documento_id').html(resp.html);
    }, 'json');
}

$('#btnNuevoDocumento').on('click', function() {
    cargarTiposDocumento();
});


$('#entidad_tipo').on('change', function() {
    let tipo = $(this).val();

    // Cargar entidades
    $.get('/modulos/documentos/acciones/get_entidades.php', { tipo: tipo }, function(resp) {
        $('#entidad_id').html(resp.html);
    }, 'json');

    // Cargar tipos de documento filtrados
    $.get('/modulos/documentos/acciones/get_tipos_documento.php', { tipo: tipo }, function(resp) {
        $('#tipo_documento_id').html(resp.html);
    }, 'json');
});

$('#tipo_documento_id').on('change', function() {
    let tipo = $(this).val();

    $.get('/modulos/documentos/acciones/get_campos_tipo.php', { tipo: tipo }, function(resp) {
        $('#contenedor_campos_tipo').html(resp.html);
    }, 'json');
});


// Cuando cambia el tipo de documento
$('#tipo_documento_id').on('change', function() {
    var tipo = $(this).val();

    $.get('/modulos/documentos/acciones/get_campos_tipo.php', { tipo: tipo }, function(resp) {
        $('#contenedor_campos_tipo').html(resp.html);
    }, 'json');
});


});
