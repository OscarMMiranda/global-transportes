// ============================================================
//  JS MAESTRO PARA EDITAR VEHÍCULO
// ============================================================

console.log("📘 editar_vehiculo.js cargado correctamente");

let ID_VEHICULO_EDITAR = null;

let TAB_CARGADO = {
    datos: false,
    ficha: false,
    config: false,
    docs: false,
    auditoria: false
};

function abrirModalEditarVehiculo(idVehiculo) {

    console.log("🔵 Abriendo modal para vehículo:", idVehiculo);

    ID_VEHICULO_EDITAR = idVehiculo;

    // ========================================================
    // RESETEAR ESTADO DE TABS
    // ========================================================
    TAB_CARGADO = {
        datos: false,
        ficha: false,
        config: false,
        docs: false,
        auditoria: false
    };

    // ========================================================
    // LIMPIAR CONTENIDO DE LOS TABS
    // ========================================================
    $("#tab-datos").html("");
    $("#tab-ficha-editar").html("");
    $("#tab-config").html("");
    $("#tab-docs").html("");
    $("#tab-auditoria").html("");

    const modalEl = document.getElementById('modalEditarVehiculo2');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // ========================================================
    // ESPERAR A QUE EL MODAL SE MUESTRE COMPLETAMENTE
    // ========================================================
    modalEl.addEventListener('shown.bs.modal', function () {

        console.log("🟢 Modal listo, tabs existen en el DOM");

        // Delegación de eventos (más estable que onclick)
        $(document).off("click", "button[data-bs-target='#tab-datos']")
                   .on("click", "button[data-bs-target='#tab-datos']", cargarTabDatos);

        $(document).off("click", "button[data-bs-target='#tab-ficha-editar']")
                   .on("click", "button[data-bs-target='#tab-ficha-editar']", cargarTabFichaEditar);

        $(document).off("click", "button[data-bs-target='#tab-config']")
                   .on("click", "button[data-bs-target='#tab-config']", cargarTabConfig);

        $(document).off("click", "button[data-bs-target='#tab-docs']")
                   .on("click", "button[data-bs-target='#tab-docs']", cargarTabDocs);

        $(document).off("click", "button[data-bs-target='#tab-auditoria']")
                   .on("click", "button[data-bs-target='#tab-auditoria']", cargarTabAuditoria);

        // Cargar primer tab
        cargarTabDatos();

    }, { once: true });
}
