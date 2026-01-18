// archivo: /modulos/seguridad/permisos/js/sidebar.js

document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".sidebar-link");
    const current = window.location.pathname;

    links.forEach(link => {
        if (link.getAttribute("href") === current) {
            link.classList.add("active");
        }
    });
});