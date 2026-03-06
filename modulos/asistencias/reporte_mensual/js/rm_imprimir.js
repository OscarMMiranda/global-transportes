// ============================================================
// archivo: rm_imprimir.js
// Sistema de impresión profesional para el reporte mensual
// ============================================================

$(document).ready(function () {
    $("#btn_imprimir").on("click", function () {
        rm_imprimir_reporte();
    });
});

function rm_imprimir_reporte() {

    let vista         = $("#filtro_vista").val();
    let empresa_id    = $("#filtro_empresa").val();
    let empresa_texto = $("#filtro_empresa option:selected").text();
    let mes           = $("#filtro_mes").val();
    let anio          = $("#filtro_anio").val();
    let mes_texto     = $("#filtro_mes option:selected").text();

    let contenido = "";

    if (vista === "tabla") {
        contenido = document.getElementById("vista_tabla").innerHTML;
    }

    if (vista === "matriz") {
        contenido = document.getElementById("vista_matriz").innerHTML;
    }

    if (vista === "mensual") {
        contenido = document.getElementById("vista_mensual").innerHTML;
    }

    if (!contenido) {
        rm_toast("error", "No hay contenido para imprimir");
        return;
    }

    // ================= ENCABEZADO DINÁMICO =================
    let encabezado_html = "";

    if (empresa_id !== "" && empresa_id !== "0" && empresa_id !== "ALL") {
        encabezado_html = `
            <div style="margin-bottom: 20px;">
                <h2 style="margin:0; padding:0;">Reporte Mensual</h2>
                <h3 style="margin:0; padding:0; font-weight: normal;">
                    Empresa: <strong>${empresa_texto}</strong>
                </h3>
                <h4 style="margin:0; padding:0; font-weight: normal;">
                    Periodo: <strong>${mes_texto} ${anio}</strong>
                </h4>
                <hr>
            </div>
        `;
    } else {
        encabezado_html = `
            <div style="margin-bottom: 20px;">
                <h2 style="margin:0; padding:0;">Reporte Mensual</h2>
                <h4 style="margin:0; padding:0; font-weight: normal;">
                    Periodo: <strong>${mes_texto} ${anio}</strong>
                </h4>
                <hr>
            </div>
        `;
    }

    // ================= LEYENDAS PARA IMPRESIÓN =================
    let leyendas_html = `
        <div style="margin-bottom:15px; font-size:12px;">
            <strong>Leyenda:</strong>
            A: Asistencia |
            T: Tardanza |
            FJ: Falta Justificada |
            FI: Falta Injustificada |
            VA: Vacaciones |
            DM: Descanso Médico |
            PN: Permiso con Goce |
            PS: Permiso sin Goce |
            FR: Franca |
            NL: No Laborable |
            F: Feriado
        </div>
    `;

    // ================= VENTANA DE IMPRESIÓN =================
    let w = window.open("", "PRINT", "height=800,width=1200");

    w.document.write(`
        <html>
        <head>
            <title>Reporte Mensual</title>
            <style>
                body { font-family: Arial; padding: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ccc; padding: 4px; font-size: 12px; }
                th { background: #f0f0f0; }
                .domingo { background: #ffe5e5 !important; }
                .sabado { background: #e5f0ff !important; }
                .corte-semana { border-left: 3px solid #000 !important; }
            </style>
        </head>
        <body>
            ${encabezado_html}
            ${leyendas_html}
            ${contenido}
        </body>
        </html>
    `);

    w.document.close();
    w.focus();
    w.print();
    w.close();
}
