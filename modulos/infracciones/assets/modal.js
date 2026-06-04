// archivo: /modulos/infracciones/assets/modal.js
// Manejo de modales del módulo Infracciones
// Base inicial — sin lógica de backend aún

const DEBUG_MODAL_INF = true;

function mlog(msg) {
    if (DEBUG_MODAL_INF) console.log("INF-MODAL:", msg);
}

mlog("modal.js cargado correctamente");

/* ============================================================
   ABRIR MODAL DE CREACIÓN
   ============================================================ */
function abrirModalCrear() {
    mlog("Abriendo modal de creación");

    // Limpiar formulario (cuando exista)
    if (typeof limpiarFormularioCrear === "function") {
        limpiarFormularioCrear();
    }

    // Abrir modal
    const modal = document.getElementById("modalCrear");
    if (modal) {
        const m = new bootstrap.Modal(modal);
        m.show();
    } else {
        mlog("modalCrear no encontrado");
    }
}

/* ============================================================
   ABRIR MODAL DE EDICIÓN
   ============================================================ */
function abrirModalEditar(id) {
    mlog("Abriendo modal de edición ID: " + id);

    // Más adelante:
    // - AJAX para obtener datos
    // - Llenar formulario
    // - Mostrar modal

    const modal = document.getElementById("modalEditar");
    if (modal) {
        const m = new bootstrap.Modal(modal);
        m.show();
    } else {
        mlog("modalEditar no encontrado");
    }
}

/* ============================================================
   ABRIR MODAL DE ELIMINACIÓN
   ============================================================ */
function abrirModalEliminar(id) {
    mlog("Abriendo modal de eliminación ID: " + id);

    // Más adelante:
    // - Cargar ID en input oculto
    // - Mostrar modal

    const modal = document.getElementById("modalEliminar");
    if (modal) {
        const m = new bootstrap.Modal(modal);
        m.show();
    } else {
        mlog("modalEliminar no encontrado");
    }
}
