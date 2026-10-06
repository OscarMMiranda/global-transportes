// ======================================================
// JS: ot_crear_validaciones.js
// RESPONSABILIDAD: Validaciones
// ======================================================

function validarNuevaOT() {

    if (!$('#crear_cliente_id').val()) {

        Swal.fire(
            'Cliente requerido',
            'Seleccione un cliente',
            'warning'
        );

        return false;
    }

    if (!$('#crear_empresa_id').val()) {

        Swal.fire(
            'Empresa requerida',
            'Seleccione una empresa',
            'warning'
        );

        return false;
    }

    if (!$('#crear_tipo_ot_id').val()) {

        Swal.fire(
            'Tipo OT requerido',
            'Seleccione un tipo OT',
            'warning'
        );

        return false;
    }

    return true;
}