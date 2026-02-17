// archivo  : /modulos/asistencias/reporte_mensual/js/tabla/rm_calcular_horas.js    
// Función para calcular las horas trabajadas a partir de la hora de entrada y salida

function rm_calcular_horas(hEntrada, hSalida) {

    if (!hEntrada || !hSalida) return "0.00";
    if (hEntrada === "00:00:00" && hSalida === "00:00:00") return "0.00";

    if (hSalida === "00:00:00") return "0.00";

    const inicio = new Date(`2000-01-01T${hEntrada}`);
    const fin    = new Date(`2000-01-01T${hSalida}`);

    const diffMs = fin - inicio;
    const horas = diffMs / 1000 / 60 / 60;

    return horas.toFixed(2);
}