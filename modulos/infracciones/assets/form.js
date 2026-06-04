// archivo: /modulos/infracciones/assets/form.js
// Manejo de formularios del módulo Infracciones
// Base inicial — sin lógica de backend aún

const DEBUG_FORM_INF = true;

function flog(msg) {
    if (DEBUG_FORM_INF) console.log("INF-FORM:", msg);
}

flog("form.js cargado correctamente");

/* ============================================================
   LIMPIAR FORMULARIO DE CREACIÓN
   ============================================================ */
function limpiarFormularioCrear() {
    flog("Limpiando formulario de creación");

    const form = document.getElementById("formCrearInfraccion");
    if (form) {
        form.reset();
    } else {
        flog("formCrearInfraccion no encontrado");
    }
}

/* ============================================================
   LIMPIAR FORMULARIO DE EDICIÓN
   ============================================================ */
function limpiarFormularioEditar() {
    flog("Limpiando formulario de edición");

    const form = document.getElementById("formEditarInfraccion");
    if (form) {
        form.reset();
    } else {
        flog("formEditarInfraccion no encontrado");
    }
}

/* ============================================================
   CARGAR DATOS EN FORMULARIO DE EDICIÓN
   (cuando tengamos AJAX)
   ============================================================ */
function cargarDatosEnFormularioEditar(data) {
    flog("Cargando datos en formulario de edición");

    if (!data) {
        flog("No se recibió data para cargar");
        return;
    }

    const form = document.getElementById("formEditarInfraccion");
    if (!form) {
        flog("formEditarInfraccion no encontrado");
        return;
    }

    // Más adelante llenaremos:
    // form.codigo.value = data.codigo;
    // form.descripcion.value = data.descripcion;
    // form.gravedad.value = data.gravedad;
    // form.puntos.value = data.puntos;
    // form.monto_base.value = data.monto_base;
    // form.entidad_emisora_id.value = data.entidad_emisora_id;
}

/* ============================================================
   VALIDACIÓN BÁSICA (placeholder)
   ============================================================ */
function validarFormulario(formId) {
    flog("Validando formulario: " + formId);

    const form = document.getElementById(formId);
    if (!form) {
        flog("Formulario no encontrado");
        return false;
    }

    // Más adelante agregaremos validaciones reales
    return true;
}
