// archivo: /modulos/vehiculos/js/detalle.js

document.addEventListener("DOMContentLoaded", function () {

    /**
     * Carga el contenido de una pestaña vía AJAX
     * @param {string} tabId - ID del contenedor (#tab-info, #tab-fotos, etc.)
     * @param {string} archivo - Ruta absoluta del archivo PHP
     */
    function cargarPestana(tabId, archivo) {
        const contenedor = document.querySelector(tabId);

        if (!contenedor) {
            console.error("❌ Contenedor no encontrado:", tabId);
            return;
        }

        // Evita recargar si ya se cargó antes
        if (contenedor.dataset.loaded === "1") {
            return;
        }

        // Mensaje de carga
        contenedor.innerHTML = `<div class="loading-text">Cargando información...</div>`;

        // Cargar vía AJAX
        $(tabId).load(`${archivo}?id=${VEHICULO_ID}`, function (response, status, xhr) {

            if (status === "error") {
                contenedor.innerHTML = `
                    <div class="alert alert-danger">
                        Error al cargar la información.<br>
                        <small>${xhr.status} - ${xhr.statusText}</small>
                    </div>`;
                console.error("❌ Error AJAX:", xhr);
                return;
            }

            // Marcar como cargado
            contenedor.dataset.loaded = "1";
        });
    }

    // Cargar pestaña inicial (Información)
    cargarPestana("#tab-info", "/modulos/vehiculos/vistas/info.php");

    // Detectar cambio de pestañas (Bootstrap 5)
    document.querySelectorAll("#vehiculoTabs .nav-link").forEach(btn => {

        btn.addEventListener("shown.bs.tab", e => {

            const target = e.target.getAttribute("data-bs-target");

            switch (target) {

                case "#tab-info":
                    cargarPestana("#tab-info", "/modulos/vehiculos/vistas/info.php");
                    break;

                case "#tab-fotos":
                    cargarPestana("#tab-fotos", "/modulos/vehiculos/vistas/fotos.php");
                    break;

                case "#tab-historial":
                    cargarPestana("#tab-historial", "/modulos/vehiculos/vistas/historial.php");
                    break;

                default:
                    console.warn("⚠️ Pestaña desconocida:", target);
            }

        });

    });

});