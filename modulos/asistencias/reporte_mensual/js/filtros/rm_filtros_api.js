// archivo: /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_api.js
// Este archivo contiene la función para realizar la búsqueda de datos según los filtros seleccionados en el reporte mensual de asistencias

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
            empresa:   RM_FILTROS_STATE.empresa,
            conductor: RM_FILTROS_STATE.conductor,
            mes:       RM_FILTROS_STATE.mes,
            anio:      RM_FILTROS_STATE.anio,
            vista:     RM_FILTROS_STATE.vista
        },
        success: function (response) {

            if (!response || !response.ok) {
                alert(response && response.msg ? response.msg : 'No se encontraron datos');
                rm_tabla_render([]);
                return;
            }

            rm_tabla_render(response.data);
        },
        error: function (xhr) {
            console.log('ERROR AJAX', xhr.status, xhr.responseText);
            alert('Error al obtener datos');
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

