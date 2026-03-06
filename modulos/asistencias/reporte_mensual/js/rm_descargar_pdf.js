// ============================================================
// archivo: /modulos/asistencias/reporte_mensual/js/rm_descargar_pdf.js
// propósito: lógica para descargar el PDF del reporte mensual
// ============================================================

$(document).ready(function () {

    $("#btn_descargar_pdf").on("click", function () {

        let vista = $("#filtro_vista").val();
        if (vista !== "matriz") {
            rm_toast("error", "Solo disponible en la vista MATRIZ");
            return;
        }

        let matriz = document.getElementById("vista_matriz");
        let leyendas = document.getElementById("leyendas_matriz");

        // 🔥 1. Quitar scroll horizontal temporalmente
        let originalOverflow = matriz.style.overflowX;
        matriz.style.overflowX = "visible";

        // 🔥 2. Expandir la matriz al ancho completo real
        let originalWidth = matriz.style.width;
        matriz.style.width = "1000px"; // Ajustable según tu matriz

        // Crear contenedor temporal
        let contenedor = document.createElement("div");
        contenedor.style.padding = "20px";
        contenedor.style.width = matriz.style.width;

        contenedor.appendChild(matriz.cloneNode(true));
        contenedor.appendChild(leyendas.cloneNode(true));

        let opciones = {
            margin: 5,
            filename: 'reporte_matriz.pdf',
            image: { type: 'jpeg', quality: 2 },
            html2canvas: { 
                scale: 0.8,
                useCORS: true,
                scrollX: 0,
                scrollY: 0
            },
            jsPDF: { 
                unit: 'mm', 
                format: 'a4', 
                orientation: 'landscape'
            }
        };

        html2pdf().set(opciones).from(contenedor).save().then(() => {

            // 🔥 3. Restaurar el estado original
            matriz.style.overflowX = originalOverflow;
            matriz.style.width = originalWidth;

        });

    });

});
