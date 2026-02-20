//  archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_calcular_horas.js
//
// ============================================================
//  MÓDULO: rm_calcular_horas.js
//  RESPONSABILIDAD: Cálculo de horas trabajadas en dos formatos
//  - HH:MM (para mostrar en tabla)
//  - Decimal (para cálculos contables)
//  COMPATIBLE: Hosting clásico, jQuery, ERP GlobalTransportes
// ============================================================


// ------------------------------------------------------------
//  1. CÁLCULO HH:MM  (FORMATO PARA TABLA)
// ------------------------------------------------------------
function rm_calcular_horas_hhmm(hEntrada, hSalida) {

    // Validaciones defensivas
    if (!hEntrada || !hSalida) return "00:00";
    if (hEntrada === "00:00:00" && hSalida === "00:00:00") return "00:00";
    if (hSalida === "00:00:00") return "00:00";

    const inicio = new Date("2000-01-01T" + hEntrada);
    const fin    = new Date("2000-01-01T" + hSalida);

    const diffMs = fin - inicio;
    if (diffMs <= 0) return "00:00";

    const totalMin = Math.floor(diffMs / 1000 / 60);
    const h = Math.floor(totalMin / 60);
    const m = totalMin % 60;

    return h.toString().padStart(2, "0") + ":" + m.toString().padStart(2, "0");
}


// ------------------------------------------------------------
//  2. CÁLCULO DECIMAL (PARA TOTALES CONTABLES)
// ------------------------------------------------------------
function rm_calcular_horas_decimal(hEntrada, hSalida) {

    // Validaciones defensivas
    if (!hEntrada || !hSalida) return 0;
    if (hEntrada === "00:00:00" && hSalida === "00:00:00") return 0;
    if (hSalida === "00:00:00") return 0;

    const inicio = new Date("2000-01-01T" + hEntrada);
    const fin    = new Date("2000-01-01T" + hSalida);

    const diffMs = fin - inicio;
    if (diffMs <= 0) return 0;

    const horas = diffMs / 1000 / 60 / 60;
    return parseFloat(horas.toFixed(2));
}


// ------------------------------------------------------------
//  3. EXPORTACIÓN DEL MÓDULO (si se usa en otros JS)
// ------------------------------------------------------------
window.rm_calcular_horas_hhmm   = rm_calcular_horas_hhmm;
window.rm_calcular_horas_decimal = rm_calcular_horas_decimal;
