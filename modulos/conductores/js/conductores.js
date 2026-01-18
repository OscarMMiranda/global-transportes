// archivo: /modulos/conductores/js/conductores.js
// ORQUESTADOR DEL MÓDULO CONDUCTORES

import { initTablaActivos, initTablaInactivos, tablaActivos, tablaInactivos } from '../assets/datatables.js';
import { registrarEventosAcciones } from '../assets/acciones.js';
import { registrarEventosModal } from '../assets/modal.js';
import { registrarEventosFormulario } from '../assets/form.js';

$(document).ready(function () {

    console.log("🚚 conductores.js cargado correctamente");

    // Inicializar DataTables
    initTablaActivos();
    initTablaInactivos();

    // Registrar eventos
    registrarEventosAcciones();
    registrarEventosModal();
    registrarEventosFormulario();

    // FIX: Ajustar columnas al cambiar de tab
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr('data-bs-target');

        if (target === '#panel-inactivos' && tablaInactivos) {
            tablaInactivos.columns.adjust().draw(false);
        }

        if (target === '#panel-activos' && tablaActivos) {
            tablaActivos.columns.adjust().draw(false);
        }
    });
});