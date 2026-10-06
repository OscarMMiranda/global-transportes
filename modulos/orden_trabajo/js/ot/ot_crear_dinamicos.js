// ======================================================
// JS: ot_crear_dinamicos.js
// RESPONSABILIDAD: Campos dinámicos
// ======================================================

$(document).on(
    'change',
    '#crear_tipo_ot_id',
    function() {

        var tipo = $(this).val();

        $('#crear_campo_dinamico')
            .hide()
            .addClass('d-none');

        if (tipo === '2') {

            $('#crear_campo_dinamico_label')
                .text('D.U.A. / D.A.M.');

            $('#crear_campo_dinamico')
                .show()
                .removeClass('d-none');

            return;
        }

        if (tipo === '3') {

            $('#crear_campo_dinamico_label')
                .text('BOOKING');

            $('#crear_campo_dinamico')
                .show()
                .removeClass('d-none');

            return;
        }

        if (tipo === '1') {

            $('#crear_campo_dinamico_label')
                .text('OTROS');

            $('#crear_campo_dinamico')
                .show()
                .removeClass('d-none');

            return;
        }

    }
);