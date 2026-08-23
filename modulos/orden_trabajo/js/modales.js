// ======================================================
//  ARCHIVO: /modulos/orden_trabajo/js/modales.js
//  RESPONSABILIDAD: Control de apertura y cierre de modales
// ======================================================

// -------------------------------
// Abrir modal CREAR
// -------------------------------
function abrirModalCrear() {

    $("#modalCrearOT").addClass("modal-visible");
    $("#modalOverlay").addClass("modal-visible");

    // Limpiar campos del formulario
    $("#formCrearOT")[0].reset();
}

// -------------------------------
// Abrir modal EDITAR
// -------------------------------
function abrirModalEditar(id) {

    $("#editar_id_ot").val(id);

    $("#modalEditarOT").addClass("modal-visible");
    $("#modalOverlay").addClass("modal-visible");

    // Mostrar loader y ocultar formulario
    $("#loaderEditarOT").show();
    $("#formEditarOT").hide();

    // Cargar datos vía AJAX (archivo ajax_crud.js)
    editarOT(id);
}

// -------------------------------
// Abrir modal ANULAR
// -------------------------------
function abrirModalAnular(id) {

    $("#anular_id_ot").val(id);

    $("#modalAnularOT").addClass("modal-visible");
    $("#modalOverlay").addClass("modal-visible");
}

// -------------------------------
// Abrir modal ELIMINAR
// -------------------------------
function abrirModalEliminar(id) {

    $("#eliminar_id_ot").val(id);

    $("#modalEliminarOT").addClass("modal-visible");
    $("#modalOverlay").addClass("modal-visible");
}

// -------------------------------
// Cerrar cualquier modal
// -------------------------------
function cerrarModal() {

    $(".modal-corp").removeClass("modal-visible");
    $("#modalOverlay").removeClass("modal-visible");
}
