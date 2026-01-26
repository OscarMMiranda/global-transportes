// archivo: /modulos/documentos_vehiculos/js/documentos_vehiculos.js

$(document).ready(function() {

    let tabla = $('#tablaVehiculos').DataTable({
        ajax: {
            url: '/modulos/documentos_vehiculos/acciones/listar_vehiculos.php',
            type: 'POST',
            dataSrc: 'data'
        },
        columns: [
            { data: 'placa' },
            { data: 'marca' },
            { data: 'anio' },
            { data: 'total_documentos' },
            { data: 'por_vencer' },
            { data: 'estado' },     // ← NUEVA COLUMNA
            { data: 'acciones' }
        ],
        responsive: true,
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        }
    });

});
