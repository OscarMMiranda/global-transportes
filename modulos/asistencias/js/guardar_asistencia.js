/*
    archivo: /modulos/asistencias/js/guardar_asistencia.js
    módulo: asistencias
*/

console.log("guardar_asistencia.js CARGADO");

import { bindGuardarAsistencia } from './guardar_asistencia/on_click.js';
import { buildPayload } from './guardar_asistencia/build_payload.js';
import { guardarAsistencia } from './guardar_asistencia/ajax_guardar.js';
import { postGuardado } from './guardar_asistencia/post_guardado.js';

bindGuardarAsistencia(() => {

    const payload = buildPayload();

    guardarAsistencia(
        payload,
        () => postGuardado(),
        (errorMsg) => alert("Error: " + errorMsg)
    );

});
