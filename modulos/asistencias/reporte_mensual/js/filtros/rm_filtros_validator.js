// archivo: /modulos/asistencias/reporte_mensual/js/filtros/rm_filtros_validator.js
// Este archivo contiene la función de validación para los filtros del reporte mensual de asistencias

function rm_filtros_validar() {

    if (!RM_FILTROS_STATE.mes) {
	    Swal.fire({
			icon: 'warning',
			title: 'Seleccione un mes',
			text: 'Por favor elija un mes para continuar.',
			confirmButtonText: 'Entendido',
			confirmButtonColor: '#3085d6'
			});
        return false;
    }

    if (!RM_FILTROS_STATE.anio) {
		Swal.fire({
    		icon: 'warning',
    		title: 'Seleccione un año',
    		text: 'Por favor elija un año para continuar.',
    		confirmButtonText: 'Entendido',
			confirmButtonColor: '#3085d6'
		});
		return false;
	}

    return true;
}
