// ======================================================
//  JS: ot_crear_modal.js
//  RESPONSABILIDAD: Abrir, inicializar y registrar nueva OT
//  GLOBAL 2026 — Arquitectura Limpia (Módulo Crear OT)
// ======================================================


// ======================================================
// POP-UP CORPORATIVO (SweetAlert2)
// ======================================================
function alertaOT(titulo, mensaje, tipo = 'warning') {
    Swal.fire({
        icon: tipo,               // warning, error, success, info
        title: titulo,
        text: mensaje,
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#0d6efd',
        heightAuto: false
    });
}


// ======================================================
// 1. INICIALIZAR CAMPOS DEL MODAL CREAR OT
// ======================================================
function inicializarCamposNuevaOT() {

    var hoy = new Date();
    var yyyy = hoy.getFullYear();
    var mm = ('0' + (hoy.getMonth() + 1)).slice(-2);
    var dd = ('0' + hoy.getDate()).slice(-2);

    var fechaActual = yyyy + '-' + mm + '-' + dd;
    $('#crear_fecha').val(fechaActual);

    $.ajax({
        url: '/modulos/orden_trabajo/api/ot_siguiente_numero_api.php',
        type: 'POST',
        data: { anio: yyyy },
        dataType: 'json',

        success: function (resp) {
            if (resp && resp.siguiente_ot) {
                $('#crear_numero_ot').val(resp.siguiente_ot);
            } else {
                $('#crear_numero_ot').val('0001-' + yyyy);
            }
        },

        error: function () {
            $('#crear_numero_ot').val('0001-' + yyyy);
        }
    });
}



// ======================================================
// 2. CARGAR CLIENTES
// ======================================================
function cargarClientesCrear() {

    $.ajax({
        url: '/modulos/orden_trabajo/api/cliente_listado_api.php',
        type: 'GET',
        dataType: 'json',

        success: function (res) {

            var html = '<option value="">Seleccione...</option>';

            if (res && res.ok && res.data.length > 0) {

                $.each(res.data, function (i, item) {
                    html += '<option value="' + item.id + '">' + item.nombre + '</option>';
                });
            }

            $('#crear_cliente_id').html(html);
        }
    });
}



// ======================================================
// 3. CARGAR TIPOS DE OT
// ======================================================
function cargarTiposOTCrear() {

    $.ajax({
        url: '/modulos/orden_trabajo/api/tipo_ot_listado_api.php',
        type: 'GET',
        dataType: 'json',

        success: function (res) {

            var html = '<option value="">Seleccione...</option>';

            if (res && res.ok && res.data.length > 0) {

                $.each(res.data, function (i, item) {
                    html += '<option value="' + item.id + '">' + item.nombre + '</option>';
                });
            }

            $('#crear_tipo_ot_id').html(html);
        }
    });
}



// ======================================================
// 4. CARGAR EMPRESAS
// ======================================================
function cargarEmpresasCrear() {

    $.ajax({
        url: '/modulos/orden_trabajo/api/empresa_listado_api.php',
        type: 'GET',
        dataType: 'json',

        success: function (res) {

            var html = '<option value="">Seleccione...</option>';

            if (res && res.ok && res.data.length > 0) {

                $.each(res.data, function (i, item) {
                    html += '<option value="' + item.id + '">' + item.razon_social + '</option>';
                });
            }

            $('#crear_empresa_id').html(html);
        }
    });
}



// ======================================================
// 5. ABRIR MODAL CREAR OT
// ======================================================
function abrirModalCrearOT() {

    console.log("OT → abrir modal CREAR");

    const form = document.getElementById("ot-form-crear");
    if (form) form.reset();

    inicializarCamposNuevaOT();

    cargarClientesCrear();
    cargarTiposOTCrear();
    cargarEmpresasCrear();

    const modalCrear = new bootstrap.Modal(document.getElementById('ot-modal-crear'));
    modalCrear.show();
}



// ======================================================
// 6. GUARDAR NUEVA OT
// ======================================================
function guardarNuevaOT() {

    console.log("OT → guardar nueva OT");

    // VALIDACIÓN CORPORATIVA
    var cliente = $('#crear_cliente_id').val();
    var tipo_ot = $('#crear_tipo_ot_id').val();
    var empresa = $('#crear_empresa_id').val();

    if (!cliente) {
        alertaOT('Validación', 'Debe seleccionar un CLIENTE.');
        return;
    }

    if (!tipo_ot) {
        alertaOT('Validación', 'Debe seleccionar un TIPO DE ORDEN.');
        return;
    }

    if (!empresa) {
        alertaOT('Validación', 'Debe seleccionar una EMPRESA.');
        return;
    }

    var formData = $('#ot-form-crear').serialize();

    $('#overlay-crear-ot').show();

    $.ajax({
        url: '/modulos/orden_trabajo/api/ot_crear_api.php',
        type: 'POST',
        data: formData,
        dataType: 'json',

        success: function (r) {

            if (r.ok) {

                if (typeof tablaOT !== "undefined") {
                    tablaOT.ajax.reload(null, false);
                }

                $('#ot-modal-crear').modal('hide');

                Swal.fire({
                    icon: 'success',
                    title: 'Orden creada',
                    text: 'La Orden de Trabajo fue registrada correctamente.',
                    confirmButtonColor: '#198754'
                });

            } else {
                alertaOT('Error', r.mensaje || 'Error al crear la OT', 'error');
            }
        },

        complete: function () {
            $('#overlay-crear-ot').hide();
        }
    });
}



// ======================================================
// 7. EVENTO DEL BOTÓN GUARDAR
// ======================================================
$('#ot-btn-guardar-crear').on('click', function () {
    guardarNuevaOT();
});



// ======================================================
// 8. CAMPO DINÁMICO SEGÚN TIPO DE OT
// ======================================================
$('#crear_tipo_ot_id').on('change', function () {

    var tipo = $(this).val();

    if (tipo == 2) {
        $('#crear_campo_dinamico_label').text('D.U.A. / D.A.M.');
        $('#crear_campo_dinamico').show();
    }
    else if (tipo == 3) {
        $('#crear_campo_dinamico_label').text('BOOKING');
        $('#crear_campo_dinamico').show();
    }
    else if (tipo == 1) {
        $('#crear_campo_dinamico_label').text('OTROS');
        $('#crear_campo_dinamico').show();
    }
    else {
        $('#crear_campo_dinamico').hide();
        $('#crear_campo_dinamico_input').val('');
    }
});
