/* --------------------------------------------------------------
   GLOBAL.JS – Funciones universales del sistema
   -------------------------------------------------------------- */

document.addEventListener("DOMContentLoaded", function () {

    console.log("global.js cargado correctamente");

    // ----------------------------------------------------------
    // 1. Notificaciones flotantes tipo ERP
    // ----------------------------------------------------------
    window.notificar = function (mensaje, tipo = "info", tiempo = 2500) {

        const div = document.createElement("div");
        div.className = `alert alert-${tipo} position-fixed top-0 end-0 m-3 shadow`;
        div.style.zIndex = "99999";
        div.style.minWidth = "250px";
        div.textContent = mensaje;

        document.body.appendChild(div);

        setTimeout(() => {
            div.classList.add("fade");
            setTimeout(() => div.remove(), 300);
        }, tiempo);
    };

    // Ejemplo:
    // notificar("Bienvenido al sistema", "success");

    // ----------------------------------------------------------
    // 2. Confirmación universal para acciones peligrosas
    // ----------------------------------------------------------
    window.confirmarAccion = function (mensaje = "¿Estás seguro de continuar?") {
        return confirm(mensaje);
    };

    // Ejemplo:
    // if (confirmarAccion("Eliminar usuario?")) { ... }

    // ----------------------------------------------------------
    // 3. Loader universal (ERP style)
    // ----------------------------------------------------------
    window.mostrarLoader = function () {
        if (document.getElementById("loader-global")) return;

        const loader = document.createElement("div");
        loader.id = "loader-global";
        loader.innerHTML = `
            <div style="
                position: fixed;
                top:0; left:0; width:100%; height:100%;
                background: rgba(0,0,0,0.4);
                display:flex; justify-content:center; align-items:center;
                z-index:99998;">
                <div class="spinner-border text-light" style="width:3rem; height:3rem;"></div>
            </div>
        `;
        document.body.appendChild(loader);
    };

    window.ocultarLoader = function () {
        const loader = document.getElementById("loader-global");
        if (loader) loader.remove();
    };

    // ----------------------------------------------------------
    // 4. Helper para AJAX (fetch simplificado)
    // ----------------------------------------------------------
    window.solicitud = async function (url, metodo = "GET", datos = null) {
        try {
            const opciones = { method: metodo };

            if (datos) {
                opciones.body = datos instanceof FormData ? datos : JSON.stringify(datos);
                if (!(datos instanceof FormData)) {
                    opciones.headers = { "Content-Type": "application/json" };
                }
            }

            const respuesta = await fetch(url, opciones);
            return await respuesta.json();

        } catch (error) {
            console.error("Error en solicitud AJAX:", error);
            notificar("Error de comunicación con el servidor", "danger");
            return null;
        }
    };

    // Ejemplo:
    // const r = await solicitud("/api/usuarios/listar.php");

    // ----------------------------------------------------------
    // 5. Helper para validar campos vacíos
    // ----------------------------------------------------------
    window.campoVacio = function (valor) {
        return valor === null || valor === undefined || valor.toString().trim() === "";
    };

    // ----------------------------------------------------------
    // 6. Helper para formatear fechas
    // ----------------------------------------------------------
    window.formatearFecha = function (fecha) {
        const f = new Date(fecha);
        if (isNaN(f.getTime())) return fecha;
        return f.toLocaleDateString("es-PE") + " " + f.toLocaleTimeString("es-PE");
    };

});