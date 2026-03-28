// archivo: /modulos/asignaciones/js/asignaciones.helpers.js
// Helpers reutilizables para selects y formateos

/**
 * Carga un select con datos desde una URL
 */
function cargarSelect(url, selector, label) {
    const $select = $(selector);
	label = label || 'opción';

    $select.prop('disabled', true)
           .empty()
           .append(`<option value="">Cargando ${label}...</option>`);

    $.getJSON(url)
        .done(data => {
            $select.empty().append(`<option value="">Seleccione ${label}</option>`);

            data.forEach(item => {
                $select.append(
                    `<option value="${item.id}">${item.nombre || item.placa}</option>`
                );
            });
        })
        .fail(() => {
            $select.empty().append(`<option value="">Error al cargar ${label}</option>`);
        })
        .always(() => $select.prop('disabled', false));
}



/**
 * Carga un select para EDITAR:
 * - Muestra primero la opción actual
 * - Luego muestra las opciones disponibles
 */
function cargarSelectConActual(url, selector, label, actualId) {

    const $select = $(selector);
    label = label || 'opción';

    $select.prop('disabled', true)
           .empty()
           .append(`<option value="">Cargando ${label}...</option>`);

    $.getJSON(url)
        .done(data => {

            $select.empty()
                   .append(`<option value="">Seleccione ${label}</option>`);

            // Opción actual
            if (actualId) {
                $select.append(
                    `<option value="${actualId}" selected>(Actual)</option>`
                );
            }

            // Disponibles
            data.forEach(item => {
                if (item.id != actualId) {
                    $select.append(
                        `<option value="${item.id}">${item.nombre || item.placa}</option>`
                    );
                }
            });

        })
        .fail(() => {
            $select.empty().append(`<option value="">Error al cargar ${label}</option>`);
        })
        .always(() => $select.prop('disabled', false));
}



/**
 * Helper opcional para REASIGNAR:
 * Similar a cargarSelectConActual pero sin marcar "(Actual)"
 */
function cargarSelectReasignar(url, selector, label, actualId) {

    const $select = $(selector);
    label = label || 'opción';

    $select.prop('disabled', true)
           .empty()
           .append(`<option value="">Cargando ${label}...</option>`);

    $.getJSON(url)
        .done(data => {

            $select.empty()
                   .append(`<option value="">Seleccione ${label}</option>`);

            // Opción actual (sin texto "actual")
            if (actualId) {
                $select.append(
                    `<option value="${actualId}" selected>${actualId}</option>`
                );
            }

            // Disponibles
            data.forEach(item => {
                if (item.id != actualId) {
                    $select.append(
                        `<option value="${item.id}">${item.nombre || item.placa}</option>`
                    );
                }
            });

        })
        .fail(() => {
            $select.empty().append(`<option value="">Error al cargar ${label}</option>`);
        })
        .always(() => $select.prop('disabled', false));
}
