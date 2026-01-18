/* --------------------------------------------------------------
   PANEL ADMIN – JS MODULAR
   -------------------------------------------------------------- */

document.addEventListener("DOMContentLoaded", function () {

    console.log("Panel Admin cargado correctamente.");

    // ----------------------------------------------------------
    // 1. Efecto hover en tarjetas (opcional)
    // ----------------------------------------------------------
    const cards = document.querySelectorAll(".card");

    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.classList.add("shadow-lg");
        });

        card.addEventListener("mouseleave", () => {
            card.classList.remove("shadow-lg");
        });
    });

    // ----------------------------------------------------------
    // 2. Notificación de bienvenida (opcional)
    // ----------------------------------------------------------
    if (typeof usuarioNombre !== "undefined") {
        console.log(`Bienvenido, ${usuarioNombre}`);
    }

    // ----------------------------------------------------------
    // 3. Función para mostrar alertas flotantes (ERP style)
    // ----------------------------------------------------------
    window.mostrarNotificacion = function (mensaje, tipo = "info") {
        const contenedor = document.createElement("div");
        contenedor.className = `alert alert-${tipo} position-fixed top-0 end-0 m-3 shadow`;
        contenedor.style.zIndex = "9999";
        contenedor.textContent = mensaje;

        document.body.appendChild(contenedor);

        setTimeout(() => {
            contenedor.classList.add("fade");
            setTimeout(() => contenedor.remove(), 300);
        }, 2500);
    };

    // ----------------------------------------------------------
    // 4. Ejemplo: notificación al cargar panel
    // ----------------------------------------------------------
    // mostrarNotificacion("Panel Admin cargado", "success");

});