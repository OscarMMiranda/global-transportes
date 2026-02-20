//  archivo  : /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_api.js
//  Funciones para manejar los filtros del reporte mensual de asistencias


// ======================================================
//  CARGAR CONDUCTORES SEGÚN EMPRESA
// ======================================================


var rm_filtros_state = {
    empresa: '',
    conductor: '',
    mes: '',
    anio: '',
    vista: 'tabla'   // <-- NECESARIO

};




// ===============================
// LEER FILTROS
// ===============================
function rm_filtros_leer() {

    rm_filtros_state.empresa   = $('#filtro_empresa').val();
    rm_filtros_state.conductor = $('#filtro_conductor').val();
    rm_filtros_state.mes       = $('#filtro_mes').val();
    rm_filtros_state.anio      = $('#filtro_anio').val();
    rm_filtros_state.vista      =    $('#filtro_vista').val();

}


// ===============================
// CARGAR CONDUCTORES POR EMPRESA
// ===============================
function rm_cargar_conductores() {

    const empresa = $('#filtro_empresa').val();

    $.ajax({
        url: 'ajax/get_conductores.php',
        type: 'POST',
        data: { empresa: empresa },
        dataType: 'json',
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


// ===============================
// BUSCAR REPORTE
// ===============================
function rm_filtros_buscar() {

    rm_filtros_leer();

    // VALIDACIÓN CORRECTA
    if (!rm_filtros_validar()) {
        return;
    }

    $.ajax({
		url: '../../acciones/obtener_reporte_mensual.php',
        type: 'POST',
        dataType: 'json',
        data: rm_filtros_state,
        success: function (response) {

            if (!response || !response.ok) {
                alert('No se encontraron datos');
                rm_render_tabla([]);
                return;
            }

        	rm_render_tabla(response.data);
        	return;


            alert('Vista no reconocida');
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('Error al obtener datos');
        }
    });
}
