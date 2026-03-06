// archivo: /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_api.js
// Este archivo contiene la función para realizar la búsqueda de datos según los filtros seleccionados en el reporte mensual de asistencias


/**
 * Realiza la búsqueda de datos según los filtros seleccionados en el reporte mensual de asistencias
 *
 * Lee los filtros actuales, valida que los filtros sean correctos, y si es así, realiza una petición AJAX para obtener los datos.
 * Si la petición es correcta, renderiza la tabla con los datos obtenidos y los totales correspondientes.
 * Si la petición falla, muestra un mensaje de error y no renderiza nada.
 */
function rm_filtros_buscar() {

    rm_filtros_leer();

    if (!rm_filtros_validar()) {
        return;
    }

    $.ajax({
        url: RM_CONFIG.url_reporte_mensual,
        type: 'POST',
        dataType: 'json',
        data: {
            empresa_id		:   RM_FILTROS_STATE.empresa,
            conductor_id	:	RM_FILTROS_STATE.conductor,
            mes				:	RM_FILTROS_STATE.mes,
            anio			:	RM_FILTROS_STATE.anio,
            vista			:	RM_FILTROS_STATE.vista
        },
        success: function (response) {

            if (!response || !response.ok) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin resultados',
                    text: response && response.msg ? response.msg : 'No se encontraron datos.',
                    confirmButtonText: 'Aceptar'
                });

                rm_render_tabla([]);
                rm_totales_render({});
                return;
            }

            // ✔ Tabla (VOLVEMOS A response.data)
            rm_render_tabla(response.data);

            // ✔ Totales
            rm_totales_render(response.totales);
        },
        error: function (xhr) {
            console.log('ERROR AJAX', xhr.status, xhr.responseText);

            Swal.fire({
                icon: 'error',
                title: 'Error de servidor',
                text: 'No se pudieron obtener los datos.',
                confirmButtonText: 'Cerrar'
            });
        }
    });
}


function rm_cargar_conductores() {

    const empresa = $('#filtro_empresa').val();

    $.ajax({
        url: 'acciones/get_conductores.php',
        type: 'POST',
        dataType: 'json',
        data: { empresa: empresa },
        success: function (resp) {

            const select = $('#filtro_conductor');
            select.empty();
            select.append(`<option value="">Todos</option>`);

            if (resp.ok && resp.data.length > 0) {
                resp.data.forEach(c => {
                    select.append(`<option value="${c.id}">${c.nombre}</option>`);
                });
            }
        },
        error: function (xhr) {
            console.log("ERROR AJAX get_conductores.php");
            console.log(xhr.responseText);
        }
    });
}

