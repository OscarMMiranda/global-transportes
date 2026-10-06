// ======================================================
//  JS: ot_filtro_semana_actions.js
//  RESPONSABILIDAD: Filtro de semanas OT
//  GLOBAL 2026
// ======================================================

$(document).on('change', '#filtro_semana', function () {

    console.log('[OT] Semana seleccionada:', $(this).val());

    if (typeof tablaOT !== 'undefined' && tablaOT !== null) {

        tablaOT.ajax.reload(function (json) {

            console.log(
                '[OT] Registros recibidos:',
                json.data.length
            );

        }, false);

    } else {

        console.warn('[OT] tablaOT no inicializada');

    }

});