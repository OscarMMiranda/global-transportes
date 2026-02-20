// archivo: /modulos/asistencias/reporte_mensual/js/rm_init.js
// Este archivo se encarga de inicializar el módulo de reporte mensual de asistencias, cargando los datos necesarios y configurando los eventos

// Cargar configuración y estado
// (si luego quieres cargar configuración desde un archivo externo, aquí va --- IGNORE ---)
// (si luego quieres cargar estado inicial desde un archivo externo, aquí va --- IGNORE ---)

// Cargar eventos
// (si luego quieres cargar eventos desde un archivo externo, aquí va --- IGNORE ---)   
$(document).ready(function () {

    console.log("Módulo Reporte Mensual inicializado");

    // Cargar conductores según empresa seleccionada
    rm_cargar_conductores();

});
