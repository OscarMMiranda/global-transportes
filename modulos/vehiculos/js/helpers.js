// ============================================================
//  HELPERS GLOBALES DEL MÓDULO VEHÍCULOS
// ============================================================

// Loader corporativo para tabs y secciones
function loaderHTML(texto = "Cargando...") {
    return `
        <div class="text-center text-muted py-5">
            <div class="spinner-border text-primary mb-3"></div>
            <div>${texto}</div>
        </div>
    `;
}

// Mensaje de error corporativo
function errorHTML(texto = "Ocurrió un error inesperado") {
    return `
        <div class="text-center text-danger py-5">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <div>${texto}</div>
        </div>
    `;
}

console.log("📘 helpers.js cargado correctamente");
