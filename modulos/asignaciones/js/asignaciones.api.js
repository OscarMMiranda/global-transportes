// archivo: /modulos/asignaciones/js/asignaciones.api.js

const API = {

    // CRUD PRINCIPAL
    listar: () => 'api/listar.php',
    obtener: id => `api/obtener.php?id=${id}`,
    guardar: () => 'api/guardar.php',
    finalizar: () => 'api/finalizar.php',
    editar: () => 'api/editar.php',
    reasignar: () => 'api/reasignar.php',

    // RECURSOS (TODOS)
    conductores: () => 'api/recursos/conductores.php',
    tractos: () => 'api/recursos/tractos.php',
    carretas: () => 'api/recursos/carretas.php',

    // RECURSOS (DISPONIBLES)
    conductoresDisponibles: () => 'api/recursos/conductores_disponibles.php',
    tractosDisponibles: () => 'api/recursos/tractos_disponibles.php',
    carretasDisponibles: () => 'api/recursos/carretas_disponibles.php'
};
